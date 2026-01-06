<?php
session_start();
require 'config/config.php';

// If user is already "logged in", redirect to a simple welcome page
if (!empty($_SESSION['logged_in_user'])) {
  header('Location: /WEB(F1COFFE)/welcome.php');
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === '' || $password === '') {
    $error = 'Isi username dan password.';
  } else {
    try {
      $pdo = get_db();
    } catch (PDOException $e) {
      // Log the error, but show a generic message to the user
      error_log($e->getMessage());
      $error = 'Terjadi masalah dengan koneksi database.';
      $pdo = null;
    }

    if ($pdo instanceof PDO) {
      // DB-backed auth
      $stmt = $pdo->prepare('SELECT username, password_hash FROM users WHERE username = ? LIMIT 1');
      $stmt->execute([$username]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$row) {
        $error = 'User tidak ditemukan. Coba daftar dulu.';
      } else {
        if (password_verify($password, $row['password_hash'])) {
        $_SESSION['logged_in_user'] = $username;
          // After login, redirect to F1X COFFE main page
          header('Location: /WEB(F1COFFE)/index.html');
          exit;
        } else {
          $error = 'Password salah.';
        }
      }
    } else {
      // session fallback
      $users = $_SESSION['users'] ?? [];
      if (!isset($users[$username])) {
        $error = 'User tidak ditemukan. Coba daftar dulu.';
      } else {
        $user = $users[$username];
        if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['logged_in_user'] = $username;
                    // After login, redirect to F1X COFFE main page
                    header('Location: /WEB(F1COFFE)/index.html');
          exit;
        } else {
          $error = 'Password salah.';
        }
      }
    }
  }
}

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="assets/css/style-starter.css">
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
  <section class="w3l-contact-info py-5">
    <div class="container py-lg-5 py-md-4">
      <div class="mx-auto" style="max-width:420px;">
  <div class="auth-card reset-style fade-in" id="authCard">
          <div style="text-align:center;margin-bottom:12px">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:72px;height:72px;border-radius:18px;background:linear-gradient(135deg,#f0f4ff,#eef6ff);box-shadow:0 6px 18px rgba(30,60,120,0.06)">
              <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 12a3 3 0 100-6 3 3 0 000 6zM17 12a3 3 0 100-6 3 3 0 000 6z" stroke="#4756d6" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
          </div>
          <h2 class="auth-title" style="margin-bottom:0.25rem;text-align:center">Masuk ke akun Anda</h2>
          <p style="text-align:center;color:#667085;margin-bottom:1rem;font-size:14px">Masukkan username dan password untuk melanjutkan</p>
          <?php if ($error): ?>
            <div class="error-box" id="serverError"><?=htmlspecialchars($error)?></div>
          <?php endif; ?>

          <form method="post" action="" id="loginForm" novalidate>
            <div style="margin-bottom:.75rem">
              <div class="input-icon">
                <span class="icon">👤</span>
                <input name="username" id="username" required maxlength="100" placeholder="Username" />
              </div>
            </div>
            <div style="margin-bottom:.75rem">
              <div class="input-icon" style="position:relative">
                <span class="icon">🔒</span>
                <input type="password" name="password" id="password" required placeholder="Password" />
                <button type="button" id="togglePwd" aria-label="toggle password" style="border:0;background:transparent;position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:18px;cursor:pointer">👁️</button>
              </div>
            </div>
            <div style="display:flex;gap:.75rem;align-items:center;justify-content:space-between;margin-top:.5rem">
              <a href="/WEB(F1COFFE)/index.html" class="btn" style="flex:1;border-radius:10px;background:#fff;border:1px solid #e6e6e9;color:#333;padding:.6rem 1rem;text-align:center">Cancel</a>
              <button type="submit" class="btn btn-style btn-primary" style="flex:1;border-radius:10px">Login</button>
            </div>
          </form>

          <div id="clientError" style="display:none;margin-top:.75rem;color:#7a1f1f;background:#fff6f6;padding:.6rem;border-radius:6px;border:1px solid #f3c2c2"></div>
          <script src="assets/js/login.js"></script>

          <p class="mt-3 text-center" style="font-size:14px;color:#57606a">Belum punya akun? <a href="/WEB(F1COFFE)/register.php">Register</a></p>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
