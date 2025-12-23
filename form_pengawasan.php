<?php
session_start();
// Pastikan username tersedia untuk header (dummy jika belum login)
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = "User"; // Default jika tidak ada session
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

        /* --- CSS HEADER --- */
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

        .logo-img {
            height: 45px;
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

        /* Container Content Box */
        .content-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Style Link Kembali */
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #004699;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        /* Form Title Style */
        .form-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-title h2 {
            color: #004699;
            font-size: 20px;
            margin-bottom: 5px;
            text-transform: uppercase;
            line-height: 1.4;
        }

        .form-title p {
            color: #666;
            font-size: 13px;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Form Group & Inputs */
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

        /* --- STYLE BARU: COUNTER (JUMLAH PU BBM) --- */
        .counter-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
            max-width: 200px; /* Batasi lebar agar rapi */
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
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid #ddd;
        }
        .counter-btn:last-child {
            border-right: none;
            border-left: 1px solid #ddd;
        }
        .counter-btn:hover {
            background-color: #e2e6ea;
        }
        .counter-btn:active {
            background-color: #dbe2e8;
        }

        .counter-input {
            width: 100%;
            border: none;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            outline: none;
            appearance: textfield; /* Hapus spinner firefox */
        }
        /* Hapus spinner chrome/safari */
        .counter-input::-webkit-outer-spin-button,
        .counter-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Spinner Loading */
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* --- STYLE ACCORDION --- */
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
            transition: 0.3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .accordion-header:hover {
            background-color: #f9fafb;
        }

        .accordion-header.active {
            background-color: #004699;
            color: white;
        }

        .icon {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .active .icon {
            transform: rotate(180deg);
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
        
        /* Utility untuk Divider form dinamis */
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

        /* Class untuk section yang disembunyikan/ditampilkan */
        .hidden-section {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
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

        <div class="content-box">
            <a href="index.php" class="back-link">&larr; Kembali ke Dashboard</a>

            <div class="form-title">
                <h2>Pelaporan Hasil Kegiatan Pengawasan UTTP</h2>
                <p>Silakan isi formulir di bawah ini dengan data yang benar dan valid.</p>
            </div>

            <form action="" method="POST">

                <div class="accordion">
                    
                    <div class="accordion-item">
                        <button type="button" class="accordion-header active">
                            I. Data Pengawasan <span class="icon">&#9660;</span>
                        </button>
                        <div class="accordion-body" style="max-height: 1000px;">
                            <div class="form-content">

                                <div class="form-group">
                                    <label>Tanggal Pengawasan</label>
                                    <input type="date" name="tgl_pengawasan" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Nomor/Identitas SPBU (Pilih dari daftar atau ketik)</label>

                                    <input type="text" name="nomor_spbu" id="cari_nomor" class="form-control"
                                        list="list-spbu" placeholder="Ketik atau pilih nomor SPBU..." autocomplete="off" required>

                                    <datalist id="list-spbu">
                                    </datalist>

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
                        </div>
                    </div>

                    <div id="dynamic-container"></div>

                </div> 
                
                <button type="submit" class="btn-submit">Simpan Laporan</button>
            </form>
        </div>
    </div>

    <script>
        // --- 1. LOGIKA SPBU (Ambil Data API) ---
        const inputNomor = document.getElementById('cari_nomor');
        const listSpbu = document.getElementById('list-spbu');
        const inputAlamat = document.getElementById('hasil_alamat');
        const inputKota = document.getElementById('hasil_kota');
        const spinner = document.getElementById('loading-spinner');

        document.addEventListener('DOMContentLoaded', () => {
            fetch('api_spbu.php?action=list')
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        result.data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.nomor_spbu;
                            listSpbu.appendChild(option);
                        });
                    }
                })
                .catch(err => console.error('Gagal memuat daftar SPBU:', err));
        });

        inputNomor.addEventListener('input', function() {
            inputAlamat.value = "";
            inputKota.value = "";
            inputAlamat.setAttribute('readonly', true);
            inputKota.setAttribute('readonly', true);
            inputAlamat.style.backgroundColor = "#f9f9f9";
            inputKota.style.backgroundColor = "#f9f9f9";
            inputAlamat.style.borderColor = "#ddd";
            inputAlamat.placeholder = "";
        });

        inputNomor.addEventListener('change', function() {
            const nomor = this.value;
            if (!nomor) return;
            spinner.style.display = 'block';

            fetch('api_spbu.php?nomor=' + encodeURIComponent(nomor))
                .then(response => response.json())
                .then(result => {
                    spinner.style.display = 'none';
                    if (result.status === 'success') {
                        inputAlamat.value = result.data.alamat;
                        inputKota.value = result.data.kota_kab;
                        inputAlamat.style.borderColor = '#28a745';
                    } else {
                        inputAlamat.placeholder = "Data baru. Silakan ketik alamat manual...";
                        inputAlamat.removeAttribute('readonly');
                        inputKota.removeAttribute('readonly');
                        inputAlamat.style.backgroundColor = "#fff";
                        inputKota.style.backgroundColor = "#fff";
                        inputAlamat.focus();
                    }
                })
                .catch(err => {
                    spinner.style.display = 'none';
                    console.error('Error ambil detail:', err);
                });
        });

        // --- 2. LOGIKA COUNTER & DYNAMIC FORM ---
        const inputJumlah = document.getElementById('jumlah_input');
        const container = document.getElementById('dynamic-container');
        const btnPlus = document.getElementById('btn-plus');
        const btnMinus = document.getElementById('btn-minus');

        function triggerInputEvent() {
            const event = new Event('input', { bubbles: true });
            inputJumlah.dispatchEvent(event);
        }

        btnPlus.addEventListener('click', function() {
            let val = parseInt(inputJumlah.value) || 0;
            inputJumlah.value = val + 1;
            triggerInputEvent(); 
        });

        btnMinus.addEventListener('click', function() {
            let val = parseInt(inputJumlah.value) || 0;
            if (val > 0) {
                inputJumlah.value = val - 1;
                triggerInputEvent(); 
            }
        });

        // --- FUNGSI TOGGLE FORM (LOGIKA PILIHAN) ---
        // Fungsi ini dipanggil saat Radio Button "Jenis Kegiatan" diklik
        function toggleSection(index, value) {
            const sectionTera = document.getElementById(`section-tera-${index}`);
            const sectionUji = document.getElementById(`section-uji-${index}`);
            
            // Sembunyikan semua dulu
            sectionTera.style.display = 'none';
            sectionUji.style.display = 'none';

            // Tampilkan sesuai pilihan
            if (value === 'tera') {
                sectionTera.style.display = 'block';
            } else if (value === 'uji') {
                sectionUji.style.display = 'block';
            }
            
            // Update tinggi accordion (agar konten tidak terpotong)
            const item = document.getElementById(`item-${index}`);
            const panel = item.querySelector('.accordion-body');
            panel.style.maxHeight = panel.scrollHeight + 500 + "px"; // Tambah buffer tinggi
        }


        // Logika Utama: Saat Angka Berubah
        inputJumlah.addEventListener('input', function() {
            let targetCount = parseInt(this.value);
            if (isNaN(targetCount) || targetCount < 0) targetCount = 0;

            const existingItems = container.children.length;

            // Jika User Menambah
            if (targetCount > existingItems) {
                for (let i = existingItems + 1; i <= targetCount; i++) {
                    
                    const htmlTemplate = `
                    <div class="accordion-item" id="item-${i}">
                        <button type="button" class="accordion-header">
                            Data PU BBM #${i} <span class="icon">&#9660;</span>
                        </button>
                        <div class="accordion-body">
                            <div class="form-content">
                                
                                <h4 style="margin-bottom:15px; color:#004699; font-size:14px; font-weight:600;">Pengawasan PU BBM No. ${i}</h4>

                                <div class="form-group">
                                    <label>Merek</label>
                                    <input type="text" name="detail[${i}][merek]" class="form-control" placeholder="Contoh: Tatsuno">
                                </div>

                                <div class="form-group">
                                    <label>Tipe / Model</label>
                                    <input type="text" name="detail[${i}][tipe]" class="form-control">
                                </div>
                                
                                <div class="form-group" style="background:#eef4fa; padding:15px; border-radius:8px;">
                                    <label style="color:#004699; margin-bottom:10px; display:block; font-weight:600;">Jenis Kegiatan</label>
                                    
                                    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                                        <label style="cursor:pointer; display:flex; align-items:center; font-size:14px;">
                                            <input type="radio" name="detail[${i}][jenis_kegiatan]" value="tera" 
                                                   onchange="toggleSection(${i}, 'tera')" 
                                                   style="transform: scale(1.2); margin-right: 8px; accent-color: #004699;"> 
                                            Pemeriksaan Tanda Tera
                                        </label>

                                        <label style="cursor:pointer; display:flex; align-items:center; font-size:14px;">
                                            <input type="radio" name="detail[${i}][jenis_kegiatan]" value="uji" 
                                                   onchange="toggleSection(${i}, 'uji')" 
                                                   style="transform: scale(1.2); margin-right: 8px; accent-color: #004699;"> 
                                            Pengujian Kebenaran
                                        </label>
                                    </div>
                                </div>

                                <div id="section-tera-${i}" class="hidden-section">
                                    <div class="form-divider">Hasil Pemeriksaan Tanda Tera</div>
                                    <div class="form-group">
                                        <label>Status Tanda Tera</label>
                                        <select name="detail[${i}][tanda_tera]" class="form-control">
                                            <option value="">Pilih Status</option>
                                            <option value="Memenuhi">Memenuhi</option>
                                            <option value="Tidak Memenuhi">Tidak Memenuhi</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="section-uji-${i}" class="hidden-section">
                                    <div class="form-divider">Hasil Pengujian Kebenaran</div>

                                    <div class="form-group">
                                        <label>Kecepatan</label>
                                        <select name="detail[${i}][kecepatan]" class="form-control">
                                            <option value="">Pilih Kecepatan</option>
                                            <option value="Lambat">Lambat</option>
                                            <option value="Sedang">Sedang</option>
                                            <option value="Cepat">Cepat</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Kesalahan (%)</label>
                                        <input type="number" step="0.01" name="detail[${i}][kesalahan]" class="form-control" placeholder="Contoh: -0.2">
                                    </div>

                                    <div class="form-group">
                                        <label>Ketidaktepatan (%)</label>
                                        <input type="number" step="0.01" name="detail[${i}][ketidaktepatan]" class="form-control" placeholder="Contoh: 0.05">
                                    </div>

                                    <div class="form-group" style="margin-top:25px;">
                                        <label>Kesimpulan</label>
                                        <input type="text" name="detail[${i}][kesimpulan]" class="form-control" placeholder=" " style="border-color:#004699; font-weight:500;">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    `;
                    container.insertAdjacentHTML('beforeend', htmlTemplate);
                }
                initAccordion();
            }
            
            // Jika User Mengurangi
            else if (targetCount < existingItems) {
                while (container.children.length > targetCount) {
                    container.removeChild(container.lastElementChild);
                }
            }
        });


        // --- 3. LOGIKA ACCORDION (Global & Re-usable) ---
        function initAccordion() {
            const acc = document.getElementsByClassName("accordion-header");
            for (let i = 0; i < acc.length; i++) {
                acc[i].onclick = function() {
                    this.classList.toggle("active");
                    const panel = this.nextElementSibling;
                    if (panel.style.maxHeight) {
                        panel.style.maxHeight = null;
                    } else {
                        // Set max height cukup besar untuk menampung konten dinamis
                        panel.style.maxHeight = panel.scrollHeight + 1000 + "px";
                    }
                };
            }
        }

        // Jalankan sekali saat halaman pertama dimuat
        initAccordion();

    </script>

</body>
</html>