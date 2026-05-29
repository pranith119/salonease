<?php
require_once __DIR__ . "/../app/middleware.php";
require_once __DIR__ . "/../app/helpers.php";

$u = require_auth();
require_once __DIR__ . "/../partials/header.php";
?>

<main style="padding: 8rem 5%; min-height: 80vh;">
    <div style="margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Hello, <?= e($u["name"]) ?></h1>
        <p style="color: var(--text-muted);">Welcome to your <?= e($u["role"]) ?> dashboard.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <?php if ($u["role"] === "admin"): ?>
            <a href="/services/index.php" class="auth-card" style="padding: 2rem; display: block; hover: transform scale(1.02);">
                <h3 style="color: var(--primary); margin-bottom: 1rem;">Manage Services</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Update your salon's offerings and pricing.</p>
            </a>
            <a href="/customers/index.php" class="auth-card" style="padding: 2rem; display: block;">
                <h3 style="color: var(--primary); margin-bottom: 1rem;">Client Database</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem;">View and manage your loyal customers.</p>
            </a>
            <a href="/bookings/index.php" class="auth-card" style="padding: 2rem; display: block;">
                <h3 style="color: var(--primary); margin-bottom: 1rem;">All Bookings</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Monitor the complete salon schedule.</p>
            </a>
        <?php else: ?>
            <a href="/bookings/index.php" class="auth-card" style="padding: 2rem; display: block;">
                <h3 style="color: var(--primary); margin-bottom: 1rem;">My Schedule</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem;">View your upcoming appointments and tasks.</p>
            </a>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
