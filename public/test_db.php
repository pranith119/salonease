<?php
require_once __DIR__ . "/../app/db.php";

try {
    $msg = db()->query("SELECT 'Connected OK' AS msg")->fetch();
    echo $msg["msg"];
} catch (Throwable $e) {
    echo "DB error: " . $e->getMessage();
}
