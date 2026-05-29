<?php
require_once __DIR__ . "/../../app/middleware.php";
require_once __DIR__ . "/../../app/helpers.php";
require_once __DIR__ . "/../../models/Customer.php";

require_role("admin");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify($_POST["csrf"] ?? null);
    $id = (int)($_POST["delete_id"] ?? 0);
    if ($id > 0) customer_delete($id);
    redirect("/salonease/public/customers/index.php");
}

$customers = customer_all();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Customers</title></head>
<body style="font-family:system-ui; max-width:900px; margin:40px auto;">
  <h2>Customers</h2>
  <p>
    <a href="/salonease/public/dashboard.php">Dashboard</a> |
    <a href="/salonease/public/customers/create.php">+ Add Customer</a>
  </p>

  <table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Actions</th></tr>
    <?php foreach ($customers as $c): ?>
      <tr>
        <td><?= (int)$c["id"] ?></td>
        <td><?= e($c["full_name"]) ?></td>
        <td><?= e($c["phone"]) ?></td>
        <td><?= e($c["email"]) ?></td>
        <td>
          <a href="/salonease/public/customers/edit.php?id=<?= (int)$c["id"] ?>">Edit</a>
          <form method="post" style="display:inline;">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="delete_id" value="<?= (int)$c["id"] ?>">
            <button type="submit" onclick="return confirm('Delete customer?')">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
