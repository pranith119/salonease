<?php
declare(strict_types=1);

function e(?string $s): string {
    return htmlspecialchars($s ?? "", ENT_QUOTES, "UTF-8");
}

function redirect(string $path): void {
    header("Location: " . $path);
    exit;
}

function start_session(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
}

function csrf_token(): string {
    start_session();
    if (empty($_SESSION["csrf"])) $_SESSION["csrf"] = bin2hex(random_bytes(32));
    return $_SESSION["csrf"];
}

function csrf_verify(?string $token): void {
    start_session();
    if (!$token || empty($_SESSION["csrf"]) || !hash_equals($_SESSION["csrf"], $token)) {
        http_response_code(403);
        die("CSRF token invalid");
    }
}
