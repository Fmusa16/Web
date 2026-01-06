<?php
session_start();
require 'config/config.php';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = trim($_POST['nama'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $alamat = trim($_POST['alamat'] ?? '');
  $password = $_POST['password'] ?? '';
  $hp = trim($_POST['hp'] ?? '');

  if ($nama === '' || $username === '' || $password === '') {
    $errors[] = 'Nama, username, dan password wajib diisi.';
  }

  if ($username !== '' && preg_match('/[^a-zA-Z0-9_\-\.]/', $username)) {
    $errors[] = 'Username hanya boleh mengandung huruf, angka, _ atau -.';
  }

  try {
    $pdo = get_db();
  } catch (PDOException $e) {
    // Log the error, but show a generic message to the user
    error_log($e->getMessage());
    $errors[] = 'Terjadi masalah dengan koneksi database.';
    $pdo = null;
  }

  if ($pdo instanceof PDO) {
    // DB-backed registration
    // create table if not exists (safe to run)
    $pdo->exec(
      "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) UNIQUE NOT NULL,
        nama VARCHAR(200),
        alamat TEXT,
        password_hash VARCHAR(255) NOT NULL,
        hp VARCHAR(50),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // check exists
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
      $errors[] = 'Username sudah terpakai.';
    }

    if (empty($errors)) {
      $password_hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare('INSERT INTO users (username,nama,alamat,password_hash,hp) VALUES (?,?,?,?,?)');
      $stmt->execute([$username, $nama, $alamat, $password_hash, $hp]);
      // Redirect to login after successful registration
      header('Location: /WEB(F1COFFE)/login.php');
      exit;
    }
  } else {
    // Session fallback
    $users = &$_SESSION['users'];
    if (!is_array($users)) { $users = []; }
    if ($username !== '' && isset($users[$username])) {
      $errors[] = 'Username sudah terpakai.';
    }

    if (empty($errors)) {
      $password_hash = password_hash($password, PASSWORD_DEFAULT);
      $users[$username] = [
        'nama' => $nama,
        'username' => $username,
        'alamat' => $alamat,
        'password_hash' => $password_hash,
        'hp' => $hp,
        'created_at' => date('c'),
      ];
      // Redirect to login after successful registration (session fallback)
      header('Location: /WEB(F1COFFE)/login.php');
      exit;
    }
  }
}

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Buat Akun</title>
  <link rel="stylesheet" href="assets/css/style-starter.css">
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
  <section class="w3l-contact-info py-5">
    <div class="container py-lg-5 py-md-4">
      <div class="mx-auto" style="max-width:520px;">
        <div class="auth-card reset-style fade-in" id="authCard">
          <h2 class="auth-title">Buat Akun Baru</h2>

          <?php if ($errors): ?>
            <div class="error-box"><?php foreach ($errors as $e) { echo htmlspecialchars($e) . '<br>'; } ?></div>
          <?php endif; ?>
          <?php if ($success): ?>
            <div class="error-box" style="background:#effaf0;color:#166534"><?=htmlspecialchars($success)?></div>
          <?php endif; ?>

          <form method="post" action="" id="registerForm" novalidate>
            <div style="margin-bottom:.6rem">
              <div class="input-icon">
                <span class="icon">👤</span>
                <input name="nama" id="nama" required maxlength="200" placeholder="Nama lengkap" />
              </div>
            </div>
            <div style="margin-bottom:.6rem">
              <div class="input-icon">
                <span class="icon">📛</span>
                <input name="username" id="username" required maxlength="100" placeholder="Username" />
              </div>
            </div>
            <div style="margin-bottom:.6rem">
              <div class="input-icon">
                <span class="icon">🏠</span>
                <input name="alamat" id="alamat" placeholder="Alamat (opsional)" />
              </div>
            </div>
            <div style="margin-bottom:.6rem">
              <div class="input-icon" style="position:relative">
                <span class="icon">🔒</span>
                <input type="password" name="password" id="password" required placeholder="Password" />
                <button type="button" id="togglePwd" aria-label="toggle password" style="border:0;background:transparent;position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:18px;cursor:pointer">👁️</button>
              </div>
            </div>
            <div style="margin-bottom:.6rem">
              <div class="input-icon">
                <span class="icon">📱</span>
                <input name="hp" id="hp" maxlength="30" placeholder="No HP (opsional)" />
              </div>
            </div>

            <div style="display:flex;gap:.75rem;align-items:center;justify-content:space-between;margin-top:.5rem">
              <a href="/WEB(F1COFFE)/login.php" class="btn" style="flex:1;border-radius:10px;background:#fff;border:1px solid #e6e6e9;color:#333;padding:.6rem 1rem;text-align:center">Batal</a>
              <button type="submit" class="btn btn-style btn-primary" style="flex:1;border-radius:10px">Daftar</button>
            </div>
          </form>

          <div id="clientError" style="display:none;margin-top:.75rem;color:#7a1f1f;background:#fff6f6;padding:.6rem;border-radius:6px;border:1px solid #f3c2c2"></div>

          <script src="assets/js/login.js"></script>

          <p class="mt-3 text-center" style="font-size:14px;color:#57606a">Sudah punya akun? <a href="/WEB(F1COFFE)/login.php">Login</a></p>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
