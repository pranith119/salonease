<?php
declare(strict_types=1);

require_once __DIR__ . "/../app/db.php";

function customer_all(): array {
    return db()->query("SELECT * FROM customers ORDER BY id DESC")->fetchAll();
}

function customer_find(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function customer_create(string $full_name, ?string $phone, ?string $email, ?string $notes): void {
    $stmt = db()->prepare("INSERT INTO customers (full_name, phone, email, notes) VALUES (?,?,?,?)");
    $stmt->execute([$full_name, $phone, $email, $notes]);
}

function customer_update(int $id, string $full_name, ?string $phone, ?string $email, ?string $notes): void {
    $stmt = db()->prepare("UPDATE customers SET full_name=?, phone=?, email=?, notes=? WHERE id=?");
    $stmt->execute([$full_name, $phone, $email, $notes, $id]);
}

function customer_delete(int $id): void {
    $stmt = db()->prepare("DELETE FROM customers WHERE id=?");
    $stmt->execute([$id]);
}
