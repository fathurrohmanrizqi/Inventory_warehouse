<?php
session_start();
require_once 'config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); 

    $stmt = $conn->prepare("SELECT id, username, nama_lengkap FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Kredensial yang Anda masukkan tidak valid. Silakan periksa kembali username dan password Anda.";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - KOKBagus Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body class="login-page">
  <main class="login-card">
    <h1>KOKBagus</h1>
    <p>Sistem Manajemen Inventaris</p>
    
    <?php if ($error): ?>
    <div class="alert">
        <i class="bi bi-exclamation-circle-fill"></i> <?= $error ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="field">
        <label>Username</label>
        <div class="input">
            <i class="bi bi-person"></i>
            <input type="text" name="username" required autofocus placeholder="Masukkan username">
        </div>
      </div>
      <div class="field">
        <label>Password</label>
        <div class="input">
            <i class="bi bi-lock"></i>
            <input type="password" name="password" required placeholder="Masukkan password">
        </div>
      </div>
      <button type="submit" class="login-btn">Masuk ke Sistem</button>
    </form>
    <div class="login-foot">
        Lupa akses? Hubungi administrator IT.<br>
        &copy; 2026 KOKBagus Management
    </div>
  </main>
</body>
</html>
