<?php
session_start();
// Pastikan username tersedia untuk header (dummy jika belum login)
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = "User"; // Default jika tidak ada session
}

// --- LOGIKA PESAN SUKSES ---
$pesan_sukses = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Di sini logika penyimpanan ke database biasanya dilakukan
    $pesan_sukses = "Laporan berhasil dikirim! Terima kasih.";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pengawasan UTTP</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS Dasar */
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f4f6f9;
            color: #333;
            min-height: 100vh;
        }

        /* HEADER */
        .header {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
            height: 45px;
        }

        /* TENGAH HEADER (TANGGAL & JAM) - BARU */
        .header-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header-center .date {
            font-size: 12px;
            color: #888;
            margin-bottom: 2px;
            font-weight: 500;
        }

        .header-center .time {
            font-size: 18px;
            font-weight: 700;
            color: #004699;
            letter-spacing: 1px;
            line-height: 1.2;
        }

        @media (max-width: 768px) {
            .header-center {
                display: none;
            }
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
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
        }

        /* Container */
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
            padding-bottom: 80px;
        }

        .content-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #004699;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        /* Form Elements */
        .form-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-title h2 {
            color: #004699;
            font-size: 20px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .form-title p {
            color: #666;
            font-size: 13px;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }

        .required {
            color: #d93025;
            margin-left: 2px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        select.form-control {
            background-color: white;
            cursor: pointer;
        }

        /* Counter Wrapper */
        .counter-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
            max-width: 200px;
        }

        .counter-btn {
            background-color: #f8f9fa;
            border: none;
            width: 50px;
            height: 45px;
            font-size: 18px;
            font-weight: bold;
            color: #004699;
            cursor: pointer;
            border-right: 1px solid #ddd;
        }

        .counter-btn:last-child {
            border-right: none;
            border-left: 1px solid #ddd;
        }

        .counter-input {
            width: 100%;
            border: none;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            outline: none;
            -moz-appearance: textfield;
        }

        .counter-input::-webkit-outer-spin-button,
        .counter-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Spinner & Alerts */
        .spinner {
            position: absolute;
            right: 15px;
            top: 38px;
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #004699;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }

        /* Accordion */
        .accordion {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            border: 1px solid #e1e4e8;
        }

        .accordion-item {
            border-bottom: 1px solid #e1e4e8;
        }

        .accordion-item:last-child {
            border-bottom: none;
        }

        .accordion-header {
            width: 100%;
            background-color: #fff;
            padding: 20px 25px;
            text-align: left;
            border: none;
            outline: none;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .accordion-header.active {
            background-color: #004699;
            color: white;
        }

        .accordion-body {
            padding: 0 25px;
            background-color: white;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .form-content {
            padding: 25px 0;
        }

        .icon {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .active .icon {
            transform: rotate(180deg);
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #004699;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 30px;
        }

        .btn-submit:hover {
            background-color: #003377;
        }

        .form-divider {
            margin: 25px 0 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
            color: #004699;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hidden-section {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="header-left">
            <img src="https://intra.kemendag.go.id/res/assets/img/logo/logo-kemendag-linktree.svg" alt="Logo Kemendag" class="logo-img">
        </div>

        <div class="header-center">
            <span class="date" id="current-date">Memuat tanggal...</span>
            <span class="time" id="current-time">00:00:00 WIB</span>
        </div>

        <div class="header-right">
            <div class="user-info">
                <span style="display:block; font-size:12px; color:#888;">Halo, Admin</span>
                <span class="user-name"><?= htmlspecialchars($_SESSION['username']); ?></span>
            </div>
            <a href="logout.php" class="logout-btn">Keluar</a>
        </div>
    </header>

    <div class="container">

        <div class="content-box">
            <a href="index.php" class="back-link">&larr; Kembali ke Dashboard</a>

            <div class="form-title">
                <h2>Pelaporan Hasil Kegiatan Pengawasan UTTP</h2>
                <p>Silakan isi formulir di bawah ini dengan data yang benar dan valid.</p>
            </div>

            <?php if ($pesan_sukses): ?>
                <div class="alert-success"><?= $pesan_sukses ?></div>
            <?php endif; ?>

            <form action="" method="POST">

                <div class="accordion">

                    <div class="accordion-item" id="item-main">
                        <button type="button" class="accordion-header active">
                            I. Data Pengawasan <span class="icon">&#9660;</span>
                        </button>
                        <div class="accordion-body" style="max-height: 2000px;">
                            <div class="form-content">

                                <div class="form-group">
                                    <label>Tanggal Pengawasan <span class="required">*</span></label>
                                    <input type="date" name="tgl_pengawasan" class="form-control" required>
                                </div>

                                <div class="form-group" style="background:#eef4fa; padding:15px; border-radius:8px;">
                                    <label style="color:#004699; margin-bottom:10px; display:block; font-weight:600;">Kategori Objek Pengawasan</label>
                                    <div style="display: flex; gap: 20px;">
                                        <label style="cursor:pointer; display:flex; align-items:center; font-size:14px;">
                                            <input type="radio" name="kategori_objek" value="spbu" onchange="pilihKategori('spbu')" style="margin-right: 8px; accent-color: #004699;">
                                            SPBU
                                        </label>
                                        <label style="cursor:pointer; display:flex; align-items:center; font-size:14px;">
                                            <input type="radio" name="kategori_objek" value="non_spbu" onchange="pilihKategori('non_spbu')" style="margin-right: 8px; accent-color: #004699;">
                                            NON-SPBU
                                        </label>
                                    </div>
                                </div>

                                <div id="form-spbu" class="hidden-section">
                                    <div class="form-divider">Data SPBU</div>
                                    <div class="form-group">
                                        <label>Nomor/Identitas SPBU  <span class="required">*</span> </label>
                                        <input type="text" name="nomor_spbu" id="cari_nomor" class="form-control"
                                            list="list-spbu" placeholder="Ketik atau pilih nomor SPBU..." autocomplete="on">
                                        <datalist id="list-spbu"></datalist>
                                        <div class="spinner" id="loading-spinner"></div>
                                    </div>

                                    <div class="form-group">
                                        <label>Alamat Lengkap (Otomatis)</label>
                                        <textarea name="alamat_spbu" id="hasil_alamat" class="form-control" rows="2" readonly style="background-color: #f9f9f9;"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Kota/Kabupaten (Otomatis)</label>
                                        <input type="text" id="hasil_kota" class="form-control" readonly style="background-color: #f9f9f9;">
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah PU BBM Yang Diawasi</label>
                                        <div class="counter-wrapper">
                                            <button type="button" class="counter-btn" id="btn-minus">&minus;</button>
                                            <input type="number" name="jumlah_nozzle" id="jumlah_input" class="counter-input" value="0" min="0">
                                            <button type="button" class="counter-btn" id="btn-plus">&plus;</button>
                                        </div>
                                    </div>
                                </div>

                                <div id="form-non-spbu" class="hidden-section">
                                    <div class="form-divider">Data NON-SPBU</div>

                                    <div class="form-group">
                                        <label>Nama/Identitas Lokasi Non-SPBU  <span class="required">*</span> </label>
                                        <input type="text" name="identitas_non_spbu" id="cari_nomor_non" class="form-control"
                                            list="list-non-spbu" placeholder="Ketik nama perusahaan/pasar..." autocomplete="on" >
                                        <datalist id="list-non-spbu">
                                            <option value="PT. Maju Jaya Abadi">
                                            <option value="Pasar Baru Metro">
                                            <option value="Superindo Daan Mogot">
                                        </datalist>
                                        <div class="spinner" id="loading-spinner-non"></div>
                                    </div>

                                    <div class="form-group">
                                        <label>Alamat Lengkap (Otomatis)</label>
                                        <textarea name="alamat_non_spbu" id="hasil_alamat_non" class="form-control" rows="2" readonly style="background-color: #f9f9f9;"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Kota/Kabupaten (Otomatis)</label>
                                        <input type="text" id="hasil_kota_non" class="form-control" readonly style="background-color: #f9f9f9;">
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah UTTP Yang Diawasi</label>
                                        <div class="counter-wrapper">
                                            <button type="button" class="counter-btn" id="btn-minus-non">&minus;</button>
                                            <input type="number" name="jumlah_uttp_non" id="jumlah_input_non" class="counter-input" value="0" min="0">
                                            <button type="button" class="counter-btn" id="btn-plus-non">&plus;</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div id="dynamic-container"></div>

                </div>

                <button type="submit" class="btn-submit">Simpan Laporan</button>
            </form>
        </div>
    </div>

    <script>
        // --- LOGIKA JAM & TANGGAL (SAMA SEPERTI INDEX.PHP) ---
        function updateClock() {
            const now = new Date();

            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const dateString = now.toLocaleDateString('id-ID', dateOptions);

            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }).replace(/\./g, ':');

            const dateEl = document.getElementById('current-date');
            const timeEl = document.getElementById('current-time');

            if (dateEl) dateEl.textContent = dateString;
            if (timeEl) timeEl.textContent = timeString + " WIB";
        }
        setInterval(updateClock, 1000);
        updateClock();


        // --- LOGIKA KATEGORI UTAMA (SPBU vs NON-SPBU) ---
        // Variabel global untuk menyimpan mode aktif ('spbu' atau 'non_spbu')
        let modeAktif = '';

        function pilihKategori(kategori) {
            const formSpbu = document.getElementById('form-spbu');
            const formNonSpbu = document.getElementById('form-non-spbu');
            const dynamicContainer = document.getElementById('dynamic-container');

            // Reset isian dynamic ketika pindah kategori
            dynamicContainer.innerHTML = '';
            document.getElementById('jumlah_input').value = 0;
            document.getElementById('jumlah_input_non').value = 0;

            modeAktif = kategori;

            if (kategori === 'spbu') {
                formSpbu.style.display = 'block';
                formNonSpbu.style.display = 'none';
            } else {
                formSpbu.style.display = 'none';
                formNonSpbu.style.display = 'block';
            }
            resizeAccordion();
        }

        function resizeAccordion() {
            const mainItem = document.getElementById('item-main');
            const panel = mainItem.querySelector('.accordion-body');
            panel.style.maxHeight = panel.scrollHeight + 1000 + "px";
        }


        // --- 1. LOGIKA UTAMA SPBU & PERTASHOP (DATA DARI API) ---
        const inputNomor = document.getElementById('cari_nomor');
        const listSpbu = document.getElementById('list-spbu');

        // ID input untuk Alamat & Kota (Pastikan ID di HTML sesuai)
        const inputAlamat = document.getElementById('hasil_alamat');
        const inputKota = document.getElementById('hasil_kota');

        const spinner = document.getElementById('loading-spinner');

        // A. Load Daftar Nomor SPBU untuk Dropdown
        document.addEventListener('DOMContentLoaded', () => {
            fetch('api_spbu.php?action=list')
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        listSpbu.innerHTML = '';
                        result.data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.nomor_spbu;
                            listSpbu.appendChild(option);
                        });
                    }
                })
                .catch(err => console.error('Gagal memuat list:', err));
        });

  // B. Event saat nomor dipilih (SPBU)
        inputNomor.addEventListener('change', function() {
            const nomor = this.value;
            
            // --- MODIFIKASI: Jika kosong, hapus isian & reset ---
            if (!nomor) {
                enableManualInput(); // Fungsi ini sudah ada, akan mereset nilai & membuka kunci
                return;
            }

            spinner.style.display = 'block';

            // Panggil API Detail
            fetch(`api_spbu.php?action=detail&nomor=${encodeURIComponent(nomor)}`)
                .then(response => response.json())
                .then(result => {
                    spinner.style.display = 'none';

                    if (result.status === 'success' && result.data) {
                        // === BERHASIL ===
                        inputAlamat.value = result.data.alamat || "";
                        
                        // Gunakan prioritas nama kolom kota/kab
                        const dataWilayah = result.data.kota_kab || result.data.kabupaten || result.data.kota || "";
                        inputKota.value = dataWilayah;

                        setReadonly(true);

                    } else {
                        // === GAGAL / DATA TIDAK ADA ===
                        enableManualInput();
                    }
                })
                .catch(err => {
                    spinner.style.display = 'none';
                    console.error('Error:', err);
                    enableManualInput();
                });
        });

        // Fungsi Helper: Mengaktifkan mode input manual
        function enableManualInput() {
            inputAlamat.value = "";
            inputKota.value = "";
            inputAlamat.placeholder = "Isi alamat manual...";
            setReadonly(false);
        }

        // Fungsi Helper: Mengatur tampilan Readonly
        function setReadonly(isLocked) {
            if (isLocked) {
                inputAlamat.setAttribute('readonly', true);
                inputKota.setAttribute('readonly', true); // Kunci Kota
                inputAlamat.style.backgroundColor = "#f9f9f9";
                inputKota.style.backgroundColor = "#f9f9f9";
                inputAlamat.style.borderColor = '#28a745';
                inputKota.style.borderColor = '#28a745';
            } else {
                inputAlamat.removeAttribute('readonly');
                inputKota.removeAttribute('readonly'); // Buka Kunci Kota
                inputAlamat.style.backgroundColor = "#fff";
                inputKota.style.backgroundColor = "#fff";
                inputAlamat.style.borderColor = '#ddd';
                inputKota.style.borderColor = '#ddd';
            }
        }

        // --- 2. LOGIKA UTAMA NON-SPBU (PENCARIAN - SAMA SEPERTI SPBU) ---
        const inputNomorNon = document.getElementById('cari_nomor_non');
        const inputAlamatNon = document.getElementById('hasil_alamat_non');
        const inputKotaNon = document.getElementById('hasil_kota_non');
        const spinnerNon = document.getElementById('loading-spinner-non');

        // Data Dummy untuk Non-SPBU (Bisa diganti fetch API)
        const dbNonSpbu = {
            "PT. Maju Jaya Abadi": {
                alamat: "Jl. Industri Raya No. 12",
                kota: "Jakarta Timur"
            },
            "Pasar Baru Metro": {
                alamat: "Jl. KH Samanhudi",
                kota: "Jakarta Pusat"
            }
        };

      // Event saat Nama NON-SPBU dipilih/diketik
        inputNomorNon.addEventListener('change', function() {
            const nama = this.value;
            
            // --- MODIFIKASI: Jika kosong, hapus isian & reset style ---
            if (!nama) {
                inputAlamatNon.value = "";
                inputKotaNon.value = "";
                
                // Buka kunci (Readonly dihapus)
                inputAlamatNon.removeAttribute('readonly');
                inputKotaNon.removeAttribute('readonly');
                
                // Kembalikan warna background jadi putih
                inputAlamatNon.style.backgroundColor = "#fff";
                inputKotaNon.style.backgroundColor = "#fff";
                inputAlamatNon.style.borderColor = "#ddd";
                return;
            }

            // Reset field sebelum mencari
            inputAlamatNon.value = "";
            inputKotaNon.value = "";
            inputAlamatNon.setAttribute('readonly', true);
            inputKotaNon.setAttribute('readonly', true);

            spinnerNon.style.display = 'block';

            setTimeout(() => {
                spinnerNon.style.display = 'none';
                if (dbNonSpbu[nama]) {
                    inputAlamatNon.value = dbNonSpbu[nama].alamat;
                    inputKotaNon.value = dbNonSpbu[nama].kota;
                    inputAlamatNon.style.borderColor = '#28a745';
                    // Pastikan background abu-abu saat sukses
                    inputAlamatNon.style.backgroundColor = "#f9f9f9";
                    inputKotaNon.style.backgroundColor = "#f9f9f9";
                } else {
                    // Mode manual jika tidak ada di database
                    inputAlamatNon.placeholder = "Lokasi baru. Ketik alamat manual...";
                    inputAlamatNon.removeAttribute('readonly');
                    inputKotaNon.removeAttribute('readonly');
                    inputAlamatNon.style.backgroundColor = "#fff";
                    inputKotaNon.style.backgroundColor = "#fff";
                    inputAlamatNon.style.borderColor = "#ddd";
                    inputAlamatNon.focus();
                }
            }, 500);
        });

        // --- 3. LOGIKA GENERATE FORM (SHARED LOGIC) ---
        // Kita buat fungsi reusable untuk generate item accordion
        const container = document.getElementById('dynamic-container');

        // Setup Listener untuk SPBU
        setupCounterListeners('jumlah_input', 'btn-plus', 'btn-minus', 'PU BBM');
        // Setup Listener untuk NON-SPBU
        setupCounterListeners('jumlah_input_non', 'btn-plus-non', 'btn-minus-non', 'UTTP');

        function setupCounterListeners(inputId, plusId, minusId, labelItem) {
            const input = document.getElementById(inputId);
            const btnPlus = document.getElementById(plusId);
            const btnMinus = document.getElementById(minusId);

            btnPlus.addEventListener('click', function() {
                let val = parseInt(input.value) || 0;
                input.value = val + 1;
                triggerGenerate(input, labelItem);
            });

            btnMinus.addEventListener('click', function() {
                let val = parseInt(input.value) || 0;
                if (val > 0) {
                    input.value = val - 1;
                    triggerGenerate(input, labelItem);
                }
            });

            input.addEventListener('input', function() {
                triggerGenerate(input, labelItem);
            });
        }

        function triggerGenerate(inputEl, labelItem) {
            let targetCount = parseInt(inputEl.value);
            if (isNaN(targetCount) || targetCount < 0) targetCount = 0;

            const existingItems = container.children.length;

            if (targetCount > existingItems) {
                // Tambah Item
                for (let i = existingItems + 1; i <= targetCount; i++) {
                    addAccordionItem(i, labelItem);
                }
                initAccordion(); // Re-init click listener
            } else if (targetCount < existingItems) {
                // Kurang Item
                while (container.children.length > targetCount) {
                    container.removeChild(container.lastElementChild);
                }
            }
        }

        function addAccordionItem(index, labelType) {
            let formSpecificHTML = '';

            // --- LOGIKA PEMISAH (IF / ELSE) ---
            if (labelType === 'PU BBM') {
                // =======================
                // TAMPILAN KHUSUS SPBU
                // =======================
                formSpecificHTML = `
                  
                    <div class="form-group">
                        <label>Merek <span class="required">*</span></label>
                        <input type="text" name="detail[${index}][merek]" class="form-control" placeholder="Contoh: Tatsuno" required>
                    </div>
                    <div class="form-group">
                        <label>Tipe / Model <span class="required">*</span></label>
                        <input type="text" name="detail[${index}][tipe]" class="form-control" required>
                    </div> 
                    `;

            } else {
                // =======================
                // TAMPILAN KHUSUS NON-SPBU (UTTP)
                // =======================
                formSpecificHTML = `
                    <div class="form-group">
                        <label>Jenis / Nama UTTP <span class="required">*</span></label>
                        <select name="detail[${index}][jenis]" class="form-control" required>
                            <option value="" disabled selected >Pilih Jenis UTTP</option>
                            <option value="Timbangan Meja">Timbangan Meja</option>
                            <option value="Timbangan Pegas">Timbangan Pegas</option>
                            <option value="Timbangan Elektronik">Timbangan Elektronik</option>
                            <option value="Timbangan Sentisimal">Timbangan Sentisimal</option>
                            <option value="Timbangan Bobot Ingsut">Timbangan Bobot Ingsut</option>
                            <option value="Dacin">Dacin</option>
                            <option value="Neraca Emas">Neraca Emas</option>
                            <option value="Anak Timbangan">Anak Timbangan</option>
                            <option value="Timbangan Jembatan">Timbangan Jembatan</option>
                            <option value="TUM (Tangki Ukur Mobil)">TUM (Tangki Ukur Mobil)</option>
                            <option value="Meter Arus">Meter Arus</option>
                            <option value="Meter Air">Meter Air</option>
                            <option value="kWh Meter">kWh Meter</option>
                            <option value="Meter Kadar Air">Meter Kadar Air</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Merek <span class="required">*</span></label>
                        <input type="text" name="detail[${index}][merek]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tipe / Model <span class="required">*</span></label>
                        <input type="text" name="detail[${index}][tipe]" class="form-control">
                    </div> 

                    <div class="form-group">
                        <label>Kapasitas <span class="required">*</span></label>
                        <input type="text" name="detail[${index}][kapasitas]" class="form-control" required placeholder="Contoh: 15 kg / 1 ton">
                    </div>
                    <div class="form-group">
                        <label>Ketelitian / Daya Baca (Opsional)</label>
                        <input type="text" name="detail[${index}][daya_baca]" class="form-control" >
                    </div>
                `;
            }

            // --- MENYUSUN BAGIAN BAWAH (PENGUJIAN) ---
            let pengujianHTML = '';
            if (labelType === 'PU BBM') {
                // SPBU: Ada opsi Lambat/Sedang/Cepat
                pengujianHTML = `
                    <div class="form-group">
                        <label>Keterangan Teknis (Flow Rate)</label>
                        <select name="detail[${index}][kecepatan]" class="form-control">
                            <option value=""disabled selected >Pilih</option>
                            <option value="Lambat">Lambat</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Cepat">Cepat</option>
                        </select>
                    </div>`;
            } else {
                // UTTP: Kosong (Keterangan teknis dihapus)
                pengujianHTML = ``;
            }

            // --- TEMPLATE AKHIR ---
            const htmlTemplate = `
            <div class="accordion-item" id="item-${index}">
                <button type="button" class="accordion-header">
                    Data ${labelType} #${index} <span class="icon">&#9660;</span>
                </button>
                <div class="accordion-body">
                    <div class="form-content">
                        <h4 style="margin-bottom:15px; color:#004699; font-size:14px; font-weight:600;">Pengawasan ${labelType} No. ${index}</h4>
                        
                        ${formSpecificHTML}

                        <div class="form-group" style="background:#eef4fa; padding:15px; border-radius:8px;">
                            <label style="color:#004699; margin-bottom:10px; display:block; font-weight:600;">Jenis Kegiatan</label>
                            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                                <label style="cursor:pointer; display:flex; align-items:center; font-size:14px;">
                                    <input type="radio" name="detail[${index}][jenis_kegiatan]" value="tera" onchange="toggleSection(${index}, 'tera')" style="transform: scale(1.2); margin-right: 8px; accent-color: #004699;"> Pemeriksaan Tanda Tera
                                </label>
                                <label style="cursor:pointer; display:flex; align-items:center; font-size:14px;">
                                    <input type="radio" name="detail[${index}][jenis_kegiatan]" value="uji" onchange="toggleSection(${index}, 'uji')" style="transform: scale(1.2); margin-right: 8px; accent-color: #004699;"> Pengujian Kebenaran
                                </label>
                                <label style="cursor:pointer; display:flex; align-items:center; font-size:14px;">
                                    <input type="radio" name="detail[${index}][jenis_kegiatan]" value="gabungan" onchange="toggleSection(${index}, 'gabungan')" style="transform: scale(1.2); margin-right: 8px; accent-color: #004699;"> Pemeriksaan & Pengujian
                                </label>
                            </div>
                        </div>

                        <div id="section-tera-${index}" class="hidden-section">
                            <div class="form-divider">Hasil Pemeriksaan Tanda Tera</div>
                            <div class="form-group">
                                <label>Status Tanda Tera</label>
                                <select name="detail[${index}][tanda_tera]" class="form-control">
                                    <option value=""disabled selected >Pilih Status</option>
                                    <option value="Memenuhi">Memenuhi</option>
                                    <option value="Tidak Memenuhi">Tidak Memenuhi</option>
                                </select>
                            </div>
                        </div>

                        <div id="section-uji-${index}" class="hidden-section">
                            <div class="form-divider">Hasil Pengujian Kebenaran</div>
                            
                            ${pengujianHTML}

                            <div class="form-group"><label>Kesalahan (%)</label><input type="number" step="0.01" name="detail[${index}][kesalahan]" class="form-control"></div>
                            <div class="form-group"><label>Ketidaktetapan (%)</label><input type="number" step="0.01" name="detail[${index}][ketidaktetapan]" class="form-control"></div>
                            <div class="form-group" style="margin-top:25px;"><label>Kesimpulan</label><input type="text" name="detail[${index}][kesimpulan]" class="form-control" style="border-color:#004699; font-weight:500;" placeholder="Sah / Batal"></div>
                        </div>
                    </div>
                </div>
            </div>`;

            container.insertAdjacentHTML('beforeend', htmlTemplate);
        }

        // --- GLOBAL FUNCTION UTILITY ---
        function toggleSection(index, value) {
            const sectionTera = document.getElementById(`section-tera-${index}`);
            const sectionUji = document.getElementById(`section-uji-${index}`);
            sectionTera.style.display = 'none';
            sectionUji.style.display = 'none';

            if (value === 'tera') sectionTera.style.display = 'block';
            else if (value === 'uji') sectionUji.style.display = 'block';
            else if (value === 'gabungan') {
                sectionTera.style.display = 'block';
                sectionUji.style.display = 'block';
            }

            const item = document.getElementById(`item-${index}`);
            if (item) {
                const panel = item.querySelector('.accordion-body');
                panel.style.maxHeight = panel.scrollHeight + 1000 + "px";
            }
        }

        function initAccordion() {
            const acc = document.getElementsByClassName("accordion-header");
            for (let i = 0; i < acc.length; i++) {
                if (acc[i].getAttribute('data-bound') === 'true') continue;
                acc[i].setAttribute('data-bound', 'true');
                acc[i].onclick = function() {
                    this.classList.toggle("active");
                    const panel = this.nextElementSibling;
                    if (panel.style.maxHeight) panel.style.maxHeight = null;
                    else panel.style.maxHeight = panel.scrollHeight + 1000 + "px";
                };
            }
        }
        initAccordion();
    </script>
</body>

</html>
