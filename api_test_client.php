<?php

/**
 * SalonEase API Test Client
 * Demonstrates Sanctum Token Authentication and REST API Operations
 */

$baseUrl = 'http://localhost:8000/api';

echo "========================================\n";
echo "  SalonEase API Test Client\n";
echo "========================================\n\n";

// 1. Generate Token (Login)
echo "[1] Authenticating and generating token...\n";
$loginData = [
    'email' => 'admin@salonease.com', // Assumes you've created this user
    'password' => 'password',
    'scopes' => ['customers:read', 'customers:write', 'services:read', 'services:write']
];

$ch = curl_init("$baseUrl/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "Login failed (HTTP $httpCode). Ensure an admin user exists and server is running.\n";
    echo "Response: $response\n";
    exit(1);
}

$loginResult = json_decode($response, true);
$token = $loginResult['token'];
echo "Success! Token: " . substr($token, 0, 10) . "...\n\n";

// Helper function for API requests
function apiRequest($method, $url, $token, $data = null) {
    global $baseUrl;
    $ch = curl_init("$baseUrl/$url");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    $headers = [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ];

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers[] = 'Content-Type: application/json';
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['code' => $httpCode, 'body' => json_decode($response, true)];
}

// 2. Fetch Customers
echo "[2] Fetching customers...\n";
$res = apiRequest('GET', 'customers', $token);
echo "HTTP Code: {$res['code']}\n";
echo "Customers found: " . count($res['body']['data'] ?? []) . "\n\n";

// 3. Create Service
echo "[3] Creating a new service...\n";
$newService = [
    'name' => 'Premium API Haircut',
    'duration_minutes' => 60,
    'price' => 45.00
];
$res = apiRequest('POST', 'services', $token, $newService);
echo "HTTP Code: {$res['code']}\n";
if ($res['code'] === 201) {
    echo "Service Created: {$res['body']['service']['name']}\n";
    $serviceId = $res['body']['service']['id'];
} else {
    echo "Failed to create service.\n";
    print_r($res['body']);
}
echo "\n";

// 4. Update Service
if (isset($serviceId)) {
    echo "[4] Updating the service...\n";
    $updateData = ['price' => 50.00];
    $res = apiRequest('PUT', "services/{$serviceId}", $token, $updateData);
    echo "HTTP Code: {$res['code']}\n";
    if ($res['code'] === 200) {
        echo "Service price updated to: {$res['body']['service']['price']}\n";
    }
    echo "\n";
    
    // 5. Delete Service
    echo "[5] Deleting the service...\n";
    $res = apiRequest('DELETE', "services/{$serviceId}", $token);
    echo "HTTP Code: {$res['code']}\n";
    echo "Message: {$res['body']['message']}\n";
    echo "\n";
}

// 6. Logout (Revoke Token)
echo "[6] Revoking token...\n";
$res = apiRequest('POST', 'logout', $token);
echo "HTTP Code: {$res['code']}\n";
echo "Message: {$res['body']['message']}\n";

echo "\nDone!\n";
