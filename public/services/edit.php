<?php
require_once __DIR__ . "/../../app/middleware.php";
require_once __DIR__ . "/../../app/helpers.php";
require_once __DIR__ . "/../../models/Service.php";

require_role("admin");

$id = (int)($_GET["id"] ?? 0);
$service = $id ? service_find($id) : null;
if (!$service) { http_response_code(404); die("Service not found"); }

$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify($_POST["csrf"] ?? null);

    $name = trim($_POST["name"] ?? "");
    $duration = (int)($_POST["duration"] ?? 0);
    $price = (float)($_POST["price"] ?? 0);

    if ($name === "") $errors[] = "Service name required";
    if ($duration <= 0) $errors[] = "Duration must be > 0";

    if (!$errors) {
        service_update($id, $name, $duration, $price);
        redirect("/salonease/public/services/index.php");
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Service</title></head>
<body style="font-family:system-ui; max-width:520px; margin:40px auto;">
  <h2>Edit Service #<?= (int)$service["id"] ?></h2>
  <?php foreach ($errors as $err): ?><p style="color:red;"><?= e($err) ?></p><?php endforeach; ?>

  <form method="post">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <div>Name<br><input name="name" value="<?= e($_POST["name"] ?? $service["name"]) ?>"></div><br>
    <div>Duration (minutes)<br><input type="number" name="duration" value="<?= e($_POST["duration"] ?? (string)$service["duration_minutes"]) ?>"></div><br>
    <div>Price<br><input type="number" step="0.01" name="price" value="<?= e($_POST["price"] ?? (string)$service["price"]) ?>"></div><br>
    <button type="submit">Update</button>
  </form>

  <p><a href="/salonease/public/services/index.php">Back</a></p>
</body>
</html>
