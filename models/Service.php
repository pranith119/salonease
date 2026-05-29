<?php
declare(strict_types=1);

require_once __DIR__ . "/../app/db.php";

function service_all(): array {
    return db()->query("SELECT * FROM services ORDER BY id DESC")->fetchAll();
}

function service_find(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function service_create(string $name, int $duration, float $price): void {
    $stmt = db()->prepare("INSERT INTO services (name, duration_minutes, price) VALUES (?,?,?)");
    $stmt->execute([$name, $duration, $price]);
}

function service_update(int $id, string $name, int $duration, float $price): void {
    $stmt = db()->prepare("UPDATE services SET name=?, duration_minutes=?, price=? WHERE id=?");
    $stmt->execute([$name, $duration, $price, $id]);
}

function service_delete(int $id): void {
    $stmt = db()->prepare("DELETE FROM services WHERE id=?");
    $stmt->execute([$id]);
}
