<?php
require_once __DIR__ . "/app/db.php";

// CHANGE THESE VALUES
$email = "admin@example.com"; 
$new_password = "admin";

try {
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = db()->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$hash, $email]);

    if ($stmt->rowCount() > 0) {
        echo "Successfully updated password for: $email\n";
        echo "New password is: $new_password\n";
    } else {
        echo "User with email '$email' not found. Please check the email address.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
