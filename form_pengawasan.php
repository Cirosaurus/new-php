<?php
session_start();

// --- CEK LOGIN ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// --- LOGIKA DATA DAERAH (SAMA SEPERTI FORM EDUKASI) ---
$data_daerah_json = [];
$kode_map = [
    '11' => 'Nanggro Aceh Darusallam', '12' => 'Sumatera Utara', '13' => 'Sumatera Barat', '14' => 'Riau',
    '15' => 'Jambi', '16' => 'Sumatera Selatan', '17' => 'Bengkulu', '18' => 'Lampung', '19' => 'Bangka Belitung',
    '21' => 'Kepulauan Riau', '31' => 'DKI Jakarta', '32' => 'Jawa Barat', '33' => 'Jawa Tengah',
    '34' => 'Daerah Istimewa Yogyakarta', '35' => 'Jawa Timur', '36' => 'Banten', '51' => 'Bali',
    '52' => 'Nusa Tenggara Barat', '53' => 'Nusa Tenggara Timur', '61' => 'Kalimantan Barat',
    '62' => 'Kalimantan Tengah', '63' => 'Kalimantan Selatan', '64' => 'Kalimantan Timur',
    '65' => 'Kalimantan Utara', '71' => 'Sulawesi Utara', '72' => 'Sulawesi Tengah',
    '73' => 'Sulawesi Selatan', '74' => 'Sulawesi Tenggara', '75' => 'Gorontalo', '76' => 'Sulawesi Barat',
    '81' => 'Maluku', '82' => 'Maluku Utara', '91' => 'Papua', '92' => 'Papua Barat',
    '93' => 'Papua Selatan', '94' => 'Papua Tengah', '95' => 'Papua Pegunungan', '96' => 'Papua Barat Daya'
];

if (file_exists('kabupaten_kota.csv')) {
    $file = fopen('kabupaten_kota.csv', 'r');
    fgetcsv($file);
    while (($row = fgetcsv($file)) !== FALSE) {
        if (isset($row[0]) && isset($row[1])) {
            $id_wilayah = trim($row[0]);
            $nama_kab = trim($row[1]);
            $kode_prov = substr($id_wilayah, 0, 2);
            if (isset($kode_map[$kode_prov])) {
                $nama_provinsi = $kode_map[$kode_prov];
                $data_daerah_json[$nama_provinsi][] = $nama_kab;
            }
        }
    }
    fclose($file);
}
$json_daerah = json_encode($data_daerah_json);

// Pesan Sukses Dummy
$pesan_sukses = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Di sini logika simpan ke database
    $pesan_sukses = "Laporan Pengawasan berhasil dikirim!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pengawasan UTTP</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="https://intra.kemendag.go.id/favicon.ico" />

    <style>
        /* --- STYLE DASAR (Copy dari index.php & form_edukasi.php) --- */
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
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; color: #444; }
        .required { color: #d93025; margin-left: 3px; }
        .desc-text { display: block; font-size: 12px; color: #666; margin-top: -5px; margin-bottom: 10px; font-style: italic; }
        
        .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 14px; transition: 0.3s; background-color: #fff; }
        .form-control:focus { outline: none; border-color: #004699; box-shadow: 0 0 0 3px rgba(0, 70, 153, 0.1); }
        
        select.form-control {
            appearance: none; -webkit-appearance: none; -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23004699' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat; background-position: right 15px center; background-size: 16px; cursor: pointer; padding-right: 40px;
        }

        /* Radio & Checkbox Styling */
        .radio-group, .checkbox-group-stack { display: flex; flex-direction: column; gap: 10px; }
        .radio-item, .checkbox-item { display: flex; align-items: center; gap: 10px; font-size: 14px; cursor: pointer; }
        input[type="radio"], input[type="checkbox"] { accent-color: #004699; transform: scale(1.2); cursor: pointer; }

        /* HIDDEN SECTION (Conditional) */
        .hidden-section {
            display: none;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px dashed #ccc;
            margin-top: 15px;
            animation: fadeIn 0.4s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-submit { display: block; width: 100%; margin-top: 30px; padding: 15px; background-color: #004699; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background-color: #003377; }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-left">
            <img src="https://intra.kemendag.go.id/res/assets/img/logo/logo-kemendag-linktree.svg" alt="Logo Kemendag" class="logo-img">
        </div>
        <div class="header-right">
            <div class="user-info" style="text-align:right; margin-right:15px;">
                <span style="display:block; font-size:12px; color:#888;">Halo, Admin</span>
                <span class="user-name"><?= htmlspecialchars($_SESSION['username']); ?></span>
            </div>
            <a href="logout.php" class="logout-btn">Keluar</a>
        </div>
    </header>

    <div class="container">
        <a href="index.php" class="back-link">&larr; Kembali ke Dashboard</a>

        <div class="form-title">
            <h2>Pelaporan Hasil Kegiatan Pengawasan UTTP</h2>
            <p>Isi formulir pengawasan SPBU dan alat ukur lainnya.</p>
        </div>

        <?php if($pesan_sukses): ?>
            <div class="alert-success"><?= $pesan_sukses ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            
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
                                    <?php foreach (array_keys($data_daerah_json) as $prov): ?>
                                        <option value="<?= $prov ?>"><?= $prov ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kabupaten/Kota <span class="required">*</span></label>
                                <select name="kabupaten" id="kabupaten" class="form-control" required disabled>
                                    <option value=""> Pilih Kabupaten/Kota </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button type="button" class="accordion-header">
                        II. Pelaksanaan Pengawasan UTTP <span class="icon">&#9660;</span>
                    </button>
                    <div class="accordion-body">
                        <div class="form-content">
                            <div class="form-group">
                                <label>Tanggal Pengawasan <span class="required">*</span></label>
                                <input type="date" name="tgl_pengawasan" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Lokasi Pengawasan <span class="required">*</span></label>
                                <div class="radio-group">
                                    <label class="radio-item"><input type="radio" name="lokasi_pengawasan" value="SPBU" required> SPBU</label>
                                    <label class="radio-item"><input type="radio" name="lokasi_pengawasan" value="Perusahaan"> Perusahaan (Jembatan Timbang, Meter Air, dll)</label>
                                    <label class="radio-item"><input type="radio" name="lokasi_pengawasan" value="Pasar"> Pasar Tradisional</label>
                                    <label class="radio-item"><input type="radio" name="lokasi_pengawasan" value="Ritel"> Ritel Modern/Supermarket/Ekspedisi</label>
                                    <label class="radio-item"><input type="radio" name="lokasi_pengawasan" value="Lainnya"> Yang lain</label>
                                </div>
                            </div>

                            <hr style="border-top:1px solid #eee; margin:20px 0;">
                            
                            <div class="form-group">
                                <label>Nomor/Identitas SPBU <span class="required">*</span></label>
                                <input type="text" name="nomor_spbu" class="form-control" placeholder="Contoh: 34.123.45" required>
                            </div>
                            <div class="form-group">
                                <label>Alamat SPBU <span class="required">*</span></label>
                                <textarea name="alamat_spbu" class="form-control" rows="2" placeholder="Jawaban Anda" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Jumlah PU BBM yang digunakan di SPBU <span class="required">*</span></label>
                                <input type="number" name="jumlah_pu_bbm" class="form-control" placeholder="0" required>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Nozzle yang digunakan di SPBU <span class="required">*</span></label>
                                <input type="number" name="jumlah_nozzle" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button type="button" class="accordion-header">
                        III. Pemeriksaan Tanda Tera <span class="icon">&#9660;</span>
                    </button>
                    <div class="accordion-body">
                        <div class="form-content">
                            <div class="form-group">
                                <label>Jumlah PU BBM yang <b>bertanda tera sah</b> yang berlaku <span class="required">*</span></label>
                                <input type="number" name="tera_sah" class="form-control" placeholder="0" required>
                            </div>
                            <div class="form-group">
                                <label>Jumlah PU BBM yang <b>tidak bertanda tera sah</b> yang berlaku <span class="required">*</span></label>
                                <input type="number" name="tera_tidak_sah" class="form-control" placeholder="0" required>
                            </div>
                            <div class="form-group">
                                <label>Jumlah PU BBM yang <b>bertanda tera rusak</b> <span class="required">*</span></label>
                                <input type="number" name="tera_rusak" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button type="button" class="accordion-header">
                        IV. Penggunaan dan Peruntukan <span class="icon">&#9660;</span>
                    </button>
                    <div class="accordion-body">
                        <div class="form-content">
                            
                            <div class="form-group">
                                <label>Apakah terdapat modifikasi pada PU BBM yang dapat mempengaruhi hasil pengukuran? <span class="required">*</span></label>
                                <div class="radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="ada_modifikasi" value="Ada" onclick="toggleSection('section_modifikasi', true)"> Ada
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="ada_modifikasi" value="Tidak" onclick="toggleSection('section_modifikasi', false)"> Tidak
                                    </label>
                                </div>
                            </div>

                            <div id="section_modifikasi" class="hidden-section">
                                <div class="form-group">
                                    <label style="color:#cf222e;">Detail Modifikasi</label>
                                    <textarea name="detail_modifikasi" class="form-control" placeholder="Jelaskan bentuk modifikasi yang ditemukan..."></textarea>
                                </div>
                            </div>

                            <hr style="border-top:1px solid #eee; margin:20px 0;">

                            <div class="form-group">
                                <label>Apakah terdapat dugaan pemasangan alat tambahan pada PU BBM? <span class="required">*</span></label>
                                <div class="radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="ada_alat_tambahan" value="Ada" onclick="toggleSection('section_alat_tambahan', true)"> Ada
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="ada_alat_tambahan" value="Tidak" onclick="toggleSection('section_alat_tambahan', false)"> Tidak
                                    </label>
                                </div>
                            </div>

                            <div id="section_alat_tambahan" class="hidden-section">
                                <h4 style="margin-bottom:15px; color:#004699; font-size:14px;">DETAIL PEMASANGAN ALAT TAMBAHAN</h4>
                                
                                <div class="form-group">
                                    <label>Jenis Alat Tambahan <span class="required">*</span></label>
                                    <div class="checkbox-group-stack">
                                        <label class="checkbox-item"><input type="checkbox" name="jenis_alat[]" value="PCB"> Printed Circuit Board (PCB)</label>
                                        <label class="checkbox-item"><input type="checkbox" name="jenis_alat[]" value="Switch"> Switch</label>
                                        <label class="checkbox-item"><input type="checkbox" name="jenis_alat[]" value="Lainnya"> Yang lain</label>
                                        <input type="text" name="jenis_alat_lain" class="form-control" style="margin-top:5px;" placeholder="Sebutkan jika lain...">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Dimanakah Alat Tambahan terpasang? (Merek, Tipe, No Seri UTTP) <span class="required">*</span></label>
                                    <textarea name="posisi_alat" class="form-control" placeholder="Jawaban Anda"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Dokumentasi foto Alat Tambahan <span class="required">*</span></label>
                                    <span class="desc-text">(Tautan link Google Drive/dokumentasi foto yang dapat diakses umum)</span>
                                    <input type="url" name="link_foto_alat" class="form-control" placeholder="https://...">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button type="button" class="accordion-header">
                        V. Pengujian Dalam Rangka Pengawasan <span class="icon">&#9660;</span>
                    </button>
                    <div class="accordion-body">
                        <div class="form-content">
                            <span class="desc-text" style="margin-bottom:15px;">Input jumlah nozzle pengujian dalam rangka pengawasan</span>
                            
                            <div class="form-group">
                                <label>Jumlah Nozzle yang sesuai BKD <span class="required">*</span></label>
                                <input type="number" name="nozzle_sesuai" class="form-control" placeholder="0" required>
                            </div>

                            <div class="form-group">
                                <label>Jumlah Nozzle yang tidak sesuai BKD <span class="required">*</span></label>
                                <input type="number" name="nozzle_tidak_sesuai" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <button type="submit" class="btn-submit">Kirim Laporan Pengawasan</button>
        </form>
    </div>

    <script>
        // 1. DATA DAERAH LOGIC (Sama seperti sebelumnya)
        const dataDaerah = <?php echo $json_daerah ?: '{}'; ?>;
        const selectProv = document.getElementById('provinsi');
        const selectKab = document.getElementById('kabupaten');

        selectProv.addEventListener('change', function() {
            const selectedProv = this.value;
            selectKab.innerHTML = '<option value=""> Pilih Kabupaten/Kota </option>';
            if (selectedProv && dataDaerah[selectedProv]) {
                selectKab.disabled = false;
                dataDaerah[selectedProv].sort().forEach(function(kab) {
                    const option = document.createElement('option');
                    option.value = kab;
                    option.text = kab;
                    selectKab.appendChild(option);
                });
            } else {
                selectKab.disabled = true;
            }
        });

        // 2. ACCORDION LOGIC
        const acc = document.getElementsByClassName("accordion-header");
        for (let i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                const panel = this.nextElementSibling;
                // Logika agar animasi tinggi akordeon menyesuaikan dinamis (terutama saat hidden section muncul)
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                } 
            });
        }

        // 3. TOGGLE VISIBILITY LOGIC (FUNGSI BARU)
        function toggleSection(elementId, show) {
            const section = document.getElementById(elementId);
            
            if (show) {
                section.style.display = "block";
                
                // Hack kecil: Karena ini ada di dalam accordion, saat section muncul, 
                // tinggi accordion perlu di-update agar tidak terpotong.
                // Kita cari parent accordion-body nya.
                const parentPanel = section.closest('.accordion-body');
                if (parentPanel.style.maxHeight) {
                    parentPanel.style.maxHeight = parentPanel.scrollHeight + section.scrollHeight + "px";
                }
            } else {
                section.style.display = "none";
            }
        }
    </script>

</body>
</html>