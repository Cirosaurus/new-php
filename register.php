<?php
require 'db.php';

$pesan = "";

// Jika tombol DAFTAR ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['email']); // Kita pakai input 'email' sebagai username
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        try {
            // 1. Enkripsi password (biar standar & aman)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // 2. Masukkan ke Database
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:u, :p)");
            $stmt->execute(['u' => $username, 'p' => $hashed_password]);

            // 3. Sukses? Lempar ke halaman login
            header("Location: login.php?sukses=1");
            exit;

        } catch (PDOException $e) {
            // Kalau error (misal username sudah ada)
            if ($e->getCode() == 23505) { // Kode error unik PostgreSQL
                $pesan = "Username/Email sudah terdaftar! Ganti yang lain.";
            } else {
                $pesan = "Gagal daftar: " . $e->getMessage();
            }
        }
    } else {
        $pesan = "Username dan Password harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Menggunakan Style yang SAMA dengan Login agar seragam */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html { height: 100%; font-family: 'Poppins', sans-serif; background-color: #f0f2f5; }
        .login-container { display: flex; width: 100%; height: 100vh; background-color: #004699; padding: 20px; flex-direction: column; justify-content: center; align-items: center; }
        .login-form-wrapper { width: 100%; max-width: 400px; background-color: #ffffff; padding: 32px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        h1 { font-size: 24px; color: #333; margin-bottom: 20px; text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #333; font-size: 14px; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn { width: 100%; padding: 12px; background-color: #004699; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .btn:hover { background-color: #003377; }
        .alert { background: #ffebe9; color: red; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; text-align: center; }
        .link-login { display: block; text-align: center; margin-top: 15px; font-size: 14px; color: #666; }
        .link-login a { color: #004699; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-form-wrapper">
        <h1>Buat Akun Baru</h1>

        <?php if($pesan): ?>
            <div class="alert"><?= $pesan ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Username / Email</label>
                <input type="text" name="email" required placeholder="Buat username kamu...">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required