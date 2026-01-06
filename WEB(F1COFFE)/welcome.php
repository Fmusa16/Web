<?php
session_start();
if (empty($_SESSION['logged_in_user'])) {
    header('Location: /WEB(F1COFFE)/login.php');
    exit;
}
$username = $_SESSION['logged_in_user'];
$user = $_SESSION['users'][$username] ?? ['nama' => $username];
?>
<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Welcome</title></head>
<body style="font-family:Arial,Helvetica,sans-serif;max-width:720px;margin:2rem auto;padding:1rem">
  <h1>Halo, <?=htmlspecialchars($user['nama'] ?? $username)?>!</h1>
  <p>Username: <?=htmlspecialchars($username)?></p>
  <p>Alamat: <?=htmlspecialchars($user['alamat'] ?? '')?></p>
  <p>No HP: <?=htmlspecialchars($user['hp'] ?? '')?></p>
  <p><a href="/WEB(F1COFFE)/logout.php">Logout</a></p>
</body>
</html>
