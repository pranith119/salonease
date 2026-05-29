<?php
declare(strict_types=1);

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/helpers.php";

function require_auth(): array {
    $u = auth_user();
    if (!$u) redirect("/salonease/public/login.php");
    return $u;
}

function require_role(string $role): array {
    $u = require_auth();
    if (($u["role"] ?? "") !== $role) {
        http_response_code(403);
        die("403 Forbidden - role required: " . e($role));
    }
    return $u;
}
