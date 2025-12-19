<?php
session_start(); // Mulai sesi (catat di buku tamu browser)
require 'db.php'; // Panggil koneksi database

// Jika sudah login, lempar langsung ke index
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

// Jika tombol LOGIN ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek user di database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // LOGIKA CEK PASSWORD
    if ($user && password_verify($password, $user['password'])) {
        // SUKSES! Catat di session
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        header("Location: index.php"); // Masuk ke halaman utama
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}

// --- FITUR RAHASIA: OTOMATIS BIKIN USER ADMIN JIKA BELUM ADA ---
// (Hanya untuk setup awal biar gampang)
$cek = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
if ($cek == 0) {
    $passHash = password_hash("admin123", PASSWORD_DEFAULT);
    $pdo->query("INSERT INTO users (username, password) VALUES ('admin', '$passHash')");
    $error = "User 'admin' dibuat otomatis! Password: admin123";
}
// ---------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Area</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #eee; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        .error { color: red; font-size: 14px; text-align: center; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="text-align:center">🔒 Login Dulu</h2>
        <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>