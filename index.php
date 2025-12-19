<?php
session_start();

// --- SATPAM: CEK LOGIN ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
// -------------------------

require 'db.php';

// ... (LANJUTKAN DENGAN KODE BUKU TAMU ANDA YANG TADI) ...
// ... Pastikan copy-paste kode buku tamu yang form dan tabel tadi di bawah sini ...
// ... Jangan lupa tambahkan tombol Logout di HTML-nya: <a href="logout.php">Keluar</a>
?>
<a href="logout.php" style="background:red; color:white; padding:5px 10px; text-decoration:none; border-radius:4px;">Keluar Aplikasi</a>
<hr>