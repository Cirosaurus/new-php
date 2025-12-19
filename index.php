<?php
session_start();

// --- SATPAM: CEK LOGIN ---
// Kalau belum login, tendang ke login.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pelaporan Metrologi Legal</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="https://intra.kemendag.go.id/favicon.ico" />

    <style>
        /* RESET & BASE */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9; /* Abu-abu sangat muda */
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .header {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-img {
            height: 45px; /* Sesuaikan ukuran logo */
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #004699;
            font-size: 14px;
        }

        .logout-btn {
            background-color: #ffebe9;
            color: #cf222e;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #ffc1bc;
            transition: all 0.2s;
        }
        .logout-btn:hover {
            background-color: #cf222e;
            color: white;
            border-color: #cf222e;
        }

        /* MAIN CONTENT CONTAINER */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            flex: 1; /* Agar footer terdorong ke bawah */
        }

        .page-title {
            text-align: center;
            margin-bottom: 40px;
        }
        .page-title h1 {
            color: #004699;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .page-title p {
            color: #666;
            font-size: 14px;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* GRID SYSTEM (INTI TAMPILAN MENYAMPING) */
        .menu-grid {
            display: grid;
            /* Membuat kolom otomatis: minimal lebar 300px, sisanya menyesuaikan */
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); 
            gap: 20px;
            justify-content: center;
        }

        /* CARD STYLE */
        .menu-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            border: 1px solid #e1e4e8;
            transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: flex-start; /* Align kiri */
            gap: 15px;
            position: relative;
            overflow: hidden;
        }

        /* Efek Hover Keren */
        .menu-card:hover {
            transform: translateY(-5px); /* Naik sedikit */
            box-shadow: 0 10px 20px rgba(0, 70, 153, 0.1);
            border-color: #004699;
        }

        /* Nomor Bulat Unik */
        .card-number {
            background-color: #e6f0ff;
            color: #004699;
            width: 40px;
            height: 40px;
            border-radius: 50%; /* Bulat */
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0; /* Agar tidak gepeng */
        }
        
        /* Jika Baru (Menu 5) warnanya beda */
        .menu-card.new .card-number {
            background-color: #004699;
            color: white;
        }

        .card-content h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            line-height: 1.4;
        }
        
        .card-content span {
            font-size: 12px;
            color: #888;
            font-weight: 400;
        }

        .icon-arrow {
            margin-left: auto; /* Dorong panah ke paling kanan */
            color: #ccc;
            font-weight: bold;
        }
        .menu-card:hover .icon-arrow {
            color: #004699;
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #999;
            background: white;
            border-top: 1px solid #eee;
            margin-top: 40px;
        }

    </style>
</head>
<body>

    <header class="header">
        <div class="header-left">
            <img src="https://intra.kemendag.go.id/res/assets/img/logo/logo-kemendag-linktree.svg" alt="Logo Kemendag" class="logo-img">
        </div>
        
        <div class="header-right" style="display:flex; align-items:center; gap:20px;">
            <div class="user-info">
                <span style="display:block; font-size:12px; color:#888;">Halo, Admin</span>
                <span class="user-name"><?= htmlspecialchars($_SESSION['username']); ?></span>
            </div>
            <a href="logout.php" class="logout-btn">Keluar</a>
        </div>
    </header>

    <div class="container">
        
        <div class="page-title">
            <h1>Pelaporan Hasil Kegiatan Metrologi Legal</h1>
            <p>Silakan pilih jenis pelaporan di bawah ini untuk Pemerintah Daerah. Pastikan data yang diinput sudah sesuai dan valid.</p>
        </div>

        <div class="menu-grid">

            <a href="#" class="menu-card">
                <div class="card-number">1</div>
                <div class="card-content">
                    <h3>Pelaporan Hasil Kegiatan Pengawasan UTTP</h3>
                    <span>Formulir Pengawasan</span>
                </div>
                <div class="icon-arrow">&rarr;</div>
            </a>

            <a href="#" class="menu-card">
                <div class="card-number">2</div>
                <div class="card-content">
                    <h3>Pelaporan Hasil Kegiatan Pengawasan BDKT</h3>
                    <span>Formulir Pengawasan</span>
                </div>
                <div class="icon-arrow">&rarr;</div>
            </a>

            <a href="#" class="menu-card">
                <div class="card-number">3</div>
                <div class="card-content">
                    <h3>Pelaporan Hasil Kegiatan Pengawasan SU</h3>
                    <span>Satuan Ukuran</span>
                </div>
                <div class="icon-arrow">&rarr;</div>
            </a>

            <a href="#" class="menu-card">
                <div class="card-number">4</div>
                <div class="card-content">
                    <h3>Pelaporan Hasil Kegiatan Edukasi</h3>
                    <span>Sosialisasi & Edukasi</span>
                </div>
                <div class="icon-arrow">&rarr;</div>
            </a>

            <a href="#" class="menu-card new">
                <div class="card-number">5</div>
                <div class="card-content">
                    <h3>(NEW!) Pelaporan Hasil Kegiatan Pendataan UTTP</h3>
                    <span>Pemantauan & Data Baru</span>
                </div>
                <div class="icon-arrow">&rarr;</div>
            </a>

        </div>
    </div>

    <footer>
        © 2025 Pusat Data dan Sistem Informasi Kementerian Perdagangan RI
    </footer>

</body>
</html>