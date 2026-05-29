<?php
declare(strict_types=1);

require_once __DIR__ . "/db.php";
require_once __DIR__ . "/helpers.php";

function auth_user(): ?array {
    start_session();
    return $_SESSION["user"] ?? null;
}

function login_user(array $u): void {
    start_session();
    $_SESSION["user"] = [
        "id" => (int)$u["id"],
        "name" => $u["name"],
        "email" => $u["email"],
        "role" => $u["role"],
    ];
}

function logout_user(): void {
    start_session();
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $p = session_get_cookie_params();
        setcookie(session_name(), "", time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
    }
    session_destroy();
}

function find_user_by_email(string $email): ?array {
    $stmt = db()->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    return $u ?: null;
}

function attempt_login(string $email, string $password): bool {
    $u = find_user_by_email($email);
    if (!$u) return false;
    if (!password_verify($password, $u["password_hash"])) return false;
    login_user($u);
    return true;
}
