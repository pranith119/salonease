<?php
require_once __DIR__ . "/../../app/middleware.php";
require_once __DIR__ . "/../../app/helpers.php";
require_once __DIR__ . "/../../models/Service.php";

require_role("admin");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify($_POST["csrf"] ?? null);
    $id = (int)($_POST["delete_id"] ?? 0);
    if ($id > 0) service_delete($id);
    redirect("/salonease/public/services/index.php");
}

$services = service_all();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Services</title></head>
<body style="font-family:system-ui; max-width:900px; margin:40px auto;">
  <h2>Services</h2>
  <p>
    <a href="/salonease/public/dashboard.php">Dashboard</a> |
    <a href="/salonease/public/services/create.php">+ Add Service</a>
  </p>

  <table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr><th>ID</th><th>Name</th><th>Duration</th><th>Price</th><th>Actions</th></tr>
    <?php foreach ($services as $s): ?>
      <tr>
        <td><?= (int)$s["id"] ?></td>
        <td><?= e($s["name"]) ?></td>
        <td><?= (int)$s["duration_minutes"] ?> mins</td>
        <td><?= number_format((float)$s["price"], 2) ?></td>
        <td>
          <a href="/salonease/public/services/edit.php?id=<?= (int)$s["id"] ?>">Edit</a>
          <form method="post" style="display:inline;">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="delete_id" value="<?= (int)$s["id"] ?>">
            <button type="submit" onclick="return confirm('Delete service?')">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
