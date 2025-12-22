<?php
session_start();

// --- SATPAM: CEK LOGIN ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// --- LOGIKA BACA & KLASIFIKASI CSV ---
$data_daerah_json = [];

// KAMUS KODE WILAYAH (Mapping 2 Digit Awal -> Nama Provinsi)
// Pastikan nama di sini SAMA PERSIS dengan value <option> di HTML bawah
$kode_map = [
    '11' => 'Nanggro Aceh Darusallam',
    '12' => 'Sumatera Utara',
    '13' => 'Sumatera Barat',
    '14' => 'Riau',
    '15' => 'Jambi',
    '16' => 'Sumatera Selatan',
    '17' => 'Bengkulu',
    '18' => 'Lampung',
    '19' => 'Bangka Belitung',
    '21' => 'Kepulauan Riau',
    '31' => 'DKI Jakarta',
    '32' => 'Jawa Barat',
    '33' => 'Jawa Tengah',
    '34' => 'Daerah Istimewa Yogyakarta',
    '35' => 'Jawa Timur',
    '36' => 'Banten',
    '51' => 'Bali',
    '52' => 'Nusa Tenggara Barat',
    '53' => 'Nusa Tenggara Timur',
    '61' => 'Kalimantan Barat',
    '62' => 'Kalimantan Tengah',
    '63' => 'Kalimantan Selatan',
    '64' => 'Kalimantan Timur',
    '65' => 'Kalimantan Utara',
    '71' => 'Sulawesi Utara',
    '72' => 'Sulawesi Tengah',
    '73' => 'Sulawesi Selatan',
    '74' => 'Sulawesi Tenggara',
    '75' => 'Gorontalo',
    '76' => 'Sulawesi Barat',
    '81' => 'Maluku',
    '82' => 'Maluku Utara',
    '91' => 'Papua',
    '92' => 'Papua Barat',
    '93' => 'Papua Selatan',
    '94' => 'Papua Tengah',
    '95' => 'Papua Pegunungan',
    '96' => 'Papua Barat Daya'
];

// Baca File CSV
if (file_exists('kabupaten_kota.csv')) {
    $file = fopen('kabupaten_kota.csv', 'r');
    
    // Lewati baris header ("id","name")
    fgetcsv($file);

    while (($row = fgetcsv($file)) !== FALSE) {
        // Asumsi struktur CSV: Kolom 0 = ID (11.01), Kolom 1 = Nama (Aceh Selatan)
        if (isset($row[0]) && isset($row[1])) {
            $id_wilayah = trim($row[0]);
            $nama_kab = trim($row[1]);
            
            // Ambil 2 digit pertama
            $kode_prov = substr($id_wilayah, 0, 2);
            
            // Jika kodenya ada di kamus, masukkan ke kelompok provinsi tersebut
            if (isset($kode_map[$kode_prov])) {
                $nama_provinsi = $kode_map[$kode_prov];
                $data_daerah_json[$nama_provinsi][] = $nama_kab;
            }
        }
    }
    fclose($file);
}

// Ubah ke JSON agar bisa dipakai JavaScript
$json_daerah = json_encode($data_daerah_json);

// Pesan Sukses Dummy
$pesan_sukses = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pesan_sukses = "Laporan berhasil dikirim! Terima kasih.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Edukasi Metrologi Legal</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="https://intra.kemendag.go.id/favicon.ico" />

    <style>
        /* BASE STYLE */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; color: #333; min-height: 100vh; }

        /* HEADER */
        .header { background-color: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .logo-img { height: 45px; }
        .user-name { font-weight: 600; color: #004699; font-size: 14px; }
        .logout-btn { background-color: #ffebe9; color: #cf222e; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; border: 1px solid #ffc1bc; }

        /* CONTAINER */
        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; padding-bottom: 80px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #004699; text-decoration: none; font-weight: 600; font-size: 14px; }
        .form-title { text-align: center; margin-bottom: 30px; }
        .form-title h2 { color: #004699; font-size: 20px; margin-bottom: 5px; text-transform: uppercase; line-height: 1.4; }
        .form-title p { color: #666; font-size: 13px; max-width: 600px; margin: 0 auto; }
        
        .alert-success { background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb; text-align: center; }

        /* ACCORDION */
        .accordion { background-color: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); overflow: hidden; border: 1px solid #e1e4e8; }
        .accordion-item { border-bottom: 1px solid #e1e4e8; }
        .accordion-item:last-child { border-bottom: none; }
        .accordion-header { width: 100%; background-color: #fff; padding: 20px 25px; text-align: left; border: none; outline: none; cursor: pointer; transition: 0.3s; display: flex; justify-content: space-between; align-items: center; font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 600; color: #333; }
        .accordion-header:hover { background-color: #f9fafb; }
        .accordion-header.active { background-color: #004699; color: white; }
        .icon { font-size: 12px; transition: transform 0.3s ease; }
        .active .icon { transform: rotate(180deg); }
        .accordion-body { padding: 0 25px; background-color: white; max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
        .form-content { padding: 25px 0; }

        /* INPUTS */
        .form-group { margin-bottom: 20px; position: relative; } /* Tambah relative untuk icon */
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; color: #444; }
        .required { color: #d93025; margin-left: 3px; }
        .desc-text { display: block; font-size: 12px; color: #666; margin-top: -5px; margin-bottom: 10px; font-style: italic; }
        
        .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 14px; transition: 0.3s; background-color: #fff; }
        .form-control:focus { outline: none; border-color: #004699; box-shadow: 0 0 0 3px rgba(0, 70, 153, 0.1); }
        
        /* --- CUSTOM DROPDOWN STYLE (MODERN) --- */
        select.form-control {
            appearance: none; /* Hilangkan panah default jelek */
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23004699' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
            cursor: pointer;
            padding-right: 40px; /* Ruang untuk ikon panah */
        }
        select.form-control:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
            opacity: 0.7;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23999999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); /* Panah abu-abu */
        }
        select.form-control:hover:not(:disabled) {
            border-color: #004699;
        }

        .checkbox-group { display: flex; align-items: flex-start; gap: 10px; margin-top: 10px; }
        .checkbox-group input { margin-top: 4px; accent-color: #004699; transform: scale(1.2); }
        .btn-submit { display: block; width: 100%; margin-top: 30px; padding: 15px; background-color: #004699; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background-color: #003377; }
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
        <a href="index.php" class="back-link">&larr; Kembali ke Dashboard</a>

        <div class="form-title">
            <h2>Pelaporan Hasil Kegiatan Edukasi Metrologi Legal Oleh Pemerintah Daerah</h2>
            <p>Silakan isi formulir di bawah ini dengan data yang benar dan valid.</p>
        </div>

        <?php if($pesan_sukses): ?>
            <div class="alert-success"><?= $pesan_sukses ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            
            <div class="accordion">

                <div class="accordion-item">
                    <button type="button" class="accordion-header active">
                        I. Data Daerah <span class="icon">&#9660;</span>
                    </button>
                    <div class="accordion-body" style="max-height: 1000px;">
                        <div class="form-content">
                            
                            <div class="form-group">
                                <label>Provinsi <span class="required">*</span></label>
                                <select name="provinsi" id="provinsi" class="form-control" required>
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                    <option value="DKI Jakarta">DKI Jakarta</option>
                                    <option value="Banten">Banten</option>
                                    <option value="Jawa Barat">Jawa Barat</option>
                                    <option value="Jawa Tengah">Jawa Tengah</option>
                                    <option value="Jawa Timur">Jawa Timur</option>
                                    <option value="Nanggro Aceh Darusallam">Nanggro Aceh Darusallam</option>
                                    <option value="Sumatera Utara">Sumatera Utara</option>
                                    <option value="Sumatera Barat">Sumatera Barat</option>
                                    <option value="Sumatera Selatan">Sumatera Selatan</option>
                                    <option value="Bengkulu">Bengkulu</option>
                                    <option value="Riau">Riau</option>
                                    <option value="Kepulauan Riau">Kepulauan Riau</option>
                                    <option value="Jambi">Jambi</option>
                                    <option value="Lampung">Lampung</option>
                                    <option value="Bangka Belitung">Bangka Belitung</option>
                                    <option value="Kalimantan Barat">Kalimantan Barat</option>
                                    <option value="Kalimantan Timur">Kalimantan Timur</option>
                                    <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                                    <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                                    <option value="Kalimantan Utara">Kalimantan Utara</option>
                                    <option value="Daerah Istimewa Yogyakarta">Daerah Istimewa Yogyakarta</option>
                                    <option value="Bali">Bali</option>
                                    <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                                    <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                                    <option value="Gorontalo">Gorontalo</option>
                                    <option value="Sulawesi Barat">Sulawesi Barat</option>
                                    <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                                    <option value="Sulawesi Utara">Sulawesi Utara</option>
                                    <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                                    <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                                    <option value="Maluku">Maluku</option>
                                    <option value="Maluku Utara">Maluku Utara</option>
                                    <option value="Papua">Papua</option>
                                    <option value="Papua Barat">Papua Barat</option>
                                    <option value="Papua Selatan">Papua Selatan</option>
                                    <option value="Papua Tengah">Papua Tengah</option>
                                    <option value="Papua Pegunungan">Papua Pegunungan</option>
                                    <option value="Papua Barat Daya">Papua Barat Daya</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Kabupaten/Kota <span class="required">*</span></label>
                                <span class="desc-text">(Pilih Provinsi terlebih dahulu)</span>
                                <select name="kabupaten" id="kabupaten" class="form-control" required disabled>
                                    <option value=""> Pilih Kabupaten/Kota </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tanda Daerah <span class="required">*</span></label>
                                <input type="number" name="tanda_daerah" class="form-control" placeholder="Jawaban Anda (Angka)" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button type="button" class="accordion-header">
                        II. Identitas Diri <span class="icon">&#9660;</span>
                    </button>
                    <div class="accordion-body">
                        <div class="form-content">
                            <div class="form-group">
                                <label>Nama <span class="required">*</span></label>
                                <input type="text" name="nama" class="form-control" placeholder="Jawaban Anda" required>
                            </div>
                            <div class="form-group">
                                <label>Jabatan <span class="required">*</span></label>
                                <input type="text" name="jabatan" class="form-control" placeholder="Jawaban Anda" required>
                            </div>
                            <div class="form-group">
                                <label>No HP <span class="required">*</span></label>
                                <input type="text" name="no_hp" class="form-control" placeholder="Jawaban Anda" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Akun Instagram Instansi</label>
                                <input type="text" name="ig_instansi" class="form-control" placeholder="Jawaban Anda">
                            </div>
                            <div class="form-group">
                                <label>Nama Akun Twitter/X Instansi</label>
                                <input type="text" name="twitter_instansi" class="form-control" placeholder="Jawaban Anda">
                            </div>
                            <div class="form-group">
                                <label>Nama Akun Facebook Instansi</label>
                                <input type="text" name="fb_instansi" class="form-control" placeholder="Jawaban Anda">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button type="button" class="accordion-header">
                        III. Pelaksanaan Edukasi <span class="icon">&#9660;</span>
                    </button>
                    <div class="accordion-body">
                        <div class="form-content">
                            <div class="form-group">
                                <label>Tanggal Pelaksanaan Edukasi <span class="required">*</span></label>
                                <input type="date" name="tanggal_pelaksanaan" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Lokasi Pelaksanaan Edukasi <span class="required">*</span></label>
                                <input type="text" name="lokasi_pelaksanaan" class="form-control" placeholder="Jawaban Anda" required>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Peserta <span class="required">*</span></label>
                                <input type="number" name="jumlah_peserta" class="form-control" placeholder="Jawaban Anda" required>
                            </div>
                            <div class="form-group">
                                <label>Tema Edukasi <span class="required">*</span></label>
                                <input type="text" name="tema_edukasi" class="form-control" placeholder="Jawaban Anda" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button type="button" class="accordion-header">
                        IV. Laporan Hasil Edukasi <span class="icon">&#9660;</span>
                    </button>
                    <div class="accordion-body">
                        <div class="form-content">
                            <div class="form-group">
                                <label>Laporan Hasil Edukasi <span class="required">*</span></label>
                                <span class="desc-text">tautan atau link Laporan Hasil Pengawasan Metrologi Legal diharapkan dibuka dan dapat diakses untuk umum</span>
                                <input type="url" name="link_laporan" class="form-control" placeholder="Jawaban Anda" required>
                            </div>
                            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                            <div class="form-group">
                                <label style="margin-bottom: 10px;">Apakah semua data dan dokumen yang Saudara sampaikan adalah benar dan dapat dipertanggungjawabkan? <span class="required">*</span></label>
                                <div class="checkbox-group">
                                    <input type="checkbox" name="konfirmasi_benar" id="konfirmasi" required>
                                    <label for="konfirmasi">Ya semua data dan dokumen adalah benar dan dapat dipertanggungjawabkan</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <button type="submit" class="btn-submit">Kirim</button>
        </form>
    </div>

    <script>
        // 1. DATA DARI PHP KE JS
        // JSON ini dibuat otomatis oleh PHP berdasarkan CSV yang diupload
        const dataDaerah = <?php echo $json_daerah ?: '{}'; ?>;
        
        const selectProv = document.getElementById('provinsi');
        const selectKab = document.getElementById('kabupaten');

        // 2. DETEKSI PERUBAHAN DROPDOWN PROVINSI
        selectProv.addEventListener('change', function() {
            const selectedProv = this.value;
            
            // Reset dropdown kabupaten
            selectKab.innerHTML = '<option value=""> Pilih Kabupaten/Kota </option>';
            
            // Jika data provinsi ditemukan di JSON
            if (selectedProv && dataDaerah[selectedProv]) {
                selectKab.disabled = false; // Aktifkan
                
                // Urutkan nama kabupaten (Abjad)
                const listKab = dataDaerah[selectedProv].sort();

                // Masukkan opsi ke dropdown
                listKab.forEach(function(kab) {
                    const option = document.createElement('option');
                    option.value = kab;
                    option.text = kab;
                    selectKab.appendChild(option);
                });
            } else {
                selectKab.disabled = true; // Matikan jika data tidak ada
            }
        });

        // 3. LOGIKA AKORDEON
        const acc = document.getElementsByClassName("accordion-header");
        for (let i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                const panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                } 
            });
        }
    </script>

</body>
</html>