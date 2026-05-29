<?php require_once __DIR__ . "/../app/auth.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(APP_NAME) ?> - Premium Salon Experience</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <a href="/" class="logo"><?= e(APP_NAME) ?></a>
        <nav class="nav-links">
            <a href="/">Home</a>
            <a href="#services">Services</a>
            <a href="#about">About</a>
            <?php $u = auth_user(); if ($u): ?>
                <a href="/dashboard.php" class="btn btn-outline">Dashboard</a>
                <a href="/logout.php" class="btn btn-primary">Logout</a>
            <?php else: ?>
                <a href="/login.php">Login</a>
                <a href="/register.php" class="btn btn-primary">Join Now</a>
            <?php endif; ?>
        </nav>
    </header>
