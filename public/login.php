<?php
require_once __DIR__ . "/../app/helpers.php";
require_once __DIR__ . "/../app/auth.php";

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify($_POST["csrf"] ?? null);
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!attempt_login($email, $password)) {
        $error = "Invalid email or password";
    } else {
        redirect("/dashboard.php");
    }
}
require_once __DIR__ . "/../partials/header.php";
?>

<main class="auth-container">
    <div class="auth-card">
        <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">Welcome Back</h2>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">Sign in to manage your appointments.</p>

        <?php if ($error): ?>
            <div style="background: rgba(255, 71, 87, 0.1); border: 1px solid #ff4757; color: #ff4757; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" required autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Sign In</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
            Don't have an account? <a href="/register.php" style="color: var(--primary); font-weight: 600;">Create one</a>
        </p>
    </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>

