<?php
session_start(); // Wajib ada di baris paling atas!

// --- CEK APAKAH SUDAH LOGIN? ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Kalau belum login, tendang ke halaman login
    header("Location: login.php");
    exit;
}

// --- PANGGIL DATABASE (Pake file terpisah tadi) ---
require 'db.php'; 

// --- 2. LOGIKA SIMPAN PESAN (Sama kayak tadi) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'], $_POST['pesan'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $pesan = htmlspecialchars($_POST['pesan']);

    if (!empty($nama) && !empty($pesan)) {
        $stmt = $pdo->prepare("INSERT INTO tamu (nama, pesan) VALUES (:nama, :pesan)");
        $stmt->execute(['nama' => $nama, 'pesan' => $pesan]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// --- 3. AMBIL DATA ---
$stmt = $pdo->query("SELECT * FROM tamu ORDER BY id DESC");
$semua_tamu = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Buku Tamu (Admin Mode)</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 600px; margin: auto; }
        .logout-btn { float: right; background: red; color: white; text-decoration: none; padding: 5px 10px; border-radius: 5px; font-size: 14px; }
        /* ... Sisa CSS sama kayak sebelumnya ... */
    </style>
</head>
<body>

    <a href="logout.php" class="logout-btn">Logout</a>

    <h2>📖 Buku Tamu (Halo, <?= htmlspecialchars($_SESSION['username']); ?>!)</h2>
    
    <form method="POST" action="">
        <input type="text" name="nama" placeholder="Nama" required style="width:100%; margin-bottom:10px; padding:8px;">
        <textarea name="pesan" placeholder="Pesan..." rows="3" required style="width:100%; margin-bottom:10px; padding:8px;"></textarea>
        <button type="submit" style="padding:10px; width:100%;">Kirim</button>
    </form>

    <hr>

    <ul>
        <?php foreach ($semua_tamu as $tamu): ?>
            <li style="background:#f9f9f9; border:1px solid #ddd; padding:10px; margin-bottom:5px;">
                <strong><?= htmlspecialchars($tamu['nama']); ?>:</strong> <?= htmlspecialchars($tamu['pesan']); ?>
            </li>
        <?php endforeach; ?>
    </ul>

</body>
</html>