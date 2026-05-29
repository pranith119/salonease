<?php
require_once __DIR__ . "/app/db.php";
try {
    $stmt = db()->query("SELECT name, email, role FROM users");
    $users = $stmt->fetchAll();
    if ($users) {
        echo "Registered Users:\n";
        foreach ($users as $u) {
            echo "- " . $u['name'] . " (" . $u['email'] . ") [" . $u['role'] . "]\n";
        }
    } else {
        echo "No users found in the database. Please register a new account on the website.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
