<?php
require_once __DIR__ . "/../../app/middleware.php";
require_once __DIR__ . "/../../app/helpers.php";
require_once __DIR__ . "/../../models/Customer.php";

require_role("admin");

$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify($_POST["csrf"] ?? null);

    $full_name = trim($_POST["full_name"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $notes = trim($_POST["notes"] ?? "");

    if ($full_name === "") $errors[] = "Customer name required";

    if (!$errors) {
        customer_create(
            $full_name,
            $phone !== "" ? $phone : null,
            $email !== "" ? $email : null,
            $notes !== "" ? $notes : null
        );
        redirect("/salonease/public/customers/index.php");
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Add Customer</title></head>
<body style="font-family:system-ui; max-width:520px; margin:40px auto;">
  <h2>Add Customer</h2>
  <?php foreach ($errors as $err): ?><p style="color:red;"><?= e($err) ?></p><?php endforeach; ?>

  <form method="post">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <div>Full name<br><input name="full_name" value="<?= e($_POST["full_name"] ?? "") ?>"></div><br>
    <div>Phone<br><input name="phone" value="<?= e($_POST["phone"] ?? "") ?>"></div><br>
    <div>Email<br><input name="email" value="<?= e($_POST["email"] ?? "") ?>"></div><br>
    <div>Notes<br><textarea name="notes" rows="4"><?= e($_POST["notes"] ?? "") ?></textarea></div><br>
    <button type="submit">Save</button>
  </form>

  <p><a href="/salonease/public/customers/index.php">Back</a></p>
</body>
</html>
