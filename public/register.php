<?php
require_once __DIR__ . "/../app/db.php";
require_once __DIR__ . "/../app/helpers.php";

$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify($_POST["csrf"] ?? null);

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "staff";
    if (!in_array($role, ["admin","staff"], true)) $role = "staff";

    if ($name === "") $errors[] = "Name required";
    if ($email === "") $errors[] = "Email required";
    if ($password === "") $errors[] = "Password required";

    if (!$errors) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = db()->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,?)");
            $stmt->execute([$name, $email, $hash, $role]);
            redirect("/login.php");
        } catch (Throwable $e) {
            $errors[] = "Email already exists or database error.";
        }
    }
}
require_once __DIR__ . "/../partials/header.php";
?>

<main class="auth-container">
    <div class="auth-card">
        <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">Join SalonEase</h2>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">Create your account to start booking.</p>

        <?php if ($errors): ?>
            <div style="background: rgba(255, 71, 87, 0.1); border: 1px solid #ff4757; color: #ff4757; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                <ul style="list-style: none;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="John Doe" required autofocus>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="staff" selected>Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Create Account</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
            Already have an account? <a href="/login.php" style="color: var(--primary); font-weight: 600;">Sign in</a>
        </p>
    </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
