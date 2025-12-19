<?php
require 'db.php';

$pesan = "";
$tipe_pesan = ""; // Untuk warna alert (merah/hijau)

// LOGIKA PENDAFTARAN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['email']); // Pakai input name='email'
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        try {
            // Cek dulu apakah user sudah ada?
            $stmtCek = $pdo->prepare("SELECT id FROM users WHERE username = :u");
            $stmtCek->execute(['u' => $username]);
            
            if ($stmtCek->rowCount() > 0) {
                $pesan = "Email/NIP ini sudah terdaftar!";
                $tipe_pesan = "error";
            } else {
                // Enkripsi Password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Masukkan ke Database
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:u, :p)");
                $stmt->execute(['u' => $username, 'p' => $hashed_password]);

                // Redirect ke login dengan pesan sukses
                header("Location: login.php?sukses=1");
                exit;
            }

        } catch (PDOException $e) {
            $pesan = "Gagal daftar: " . $e->getMessage();
            $tipe_pesan = "error";
        }
    } else {
        $pesan = "Mohon isi Email dan Password!";
        $tipe_pesan = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Intra Kemendag</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="https://intra.kemendag.go.id/favicon.ico" />

    <style>
        /* === COPY PASTE CSS DARI LOGIN AGAR TAMPILAN SAMA PERSIS === */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html { height: 100%; font-family: 'Poppins', sans-serif; background-color: #f0f2f5; }

        .login-container {
            display: flex; width: 100%; height: 100vh; overflow: auto;
            background-color: #004699; padding: 48px;
            flex-direction: column; justify-content: center; align-items: center;
            position: relative;
        }

        /* Background Batik */
        .login-container::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('https://intra.kemendag.go.id/res/assets/img/pages/publik/auth/bg-2025.jpg');
            background-size: cover; background-position: center; opacity: 0.1; z-index: 1;
        }

        .logo { position: relative; z-index: 2; width: 200px; margin-bottom: 40px; }

        .login-form-wrapper {
            width: 100%; max-width: 400px; position: relative; z-index: 2;
            background-color: #ffffff; padding: 32px; border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .login-form-wrapper h1 { font-size: 24px; font-weight: 600; color: #333; margin-bottom: 8px; text-align: center; }
        .login-form-wrapper p { font-size: 14px; color: #666; margin-bottom: 24px; text-align: center; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 500; color: #333; margin-bottom: 8px; }
        .form-group input { width: 100%; padding: 12px 16px; font-size: 14px; border: 1px solid #d9d9d9; border-radius: 6px; transition: border-color 0.3s; }
        .form-group input:focus { outline: none; border-color: #004699; }

        .password-wrapper { position: relative; }
        .password-toggle { position: absolute; top: 50%; right: 16px; transform: translateY(-50%); cursor: pointer; font-size: 14px; color: #888; font-weight: 600; user-select: none; }

        .btn {
            width: 100%; padding: 12px; font-size: 16px; font-weight: 600;
            border: none; border-radius: 6px; cursor: pointer; transition: all 0.3s;
            background-color: #004699; color: white; display: block; text-align: center;
        }
        .btn:hover { background-color: #003a7d; }

        .footer-text { text-align: center; margin-top: 48px; font-size: 12px; color: #ccc; z-index: 2; position: relative; }

        /* Style Alert Error */
        .alert { padding: 12px; border-radius: 6px; font-size: 14px; margin-bottom: 20px; text-align: center; }
        .alert-error { background-color: #ffebe9; color: #cf222e; border: 1px solid #ffc1bc; }

        .link-login { text-align: center; margin-top: 20px; font-size: 14px; }
        .link-login a { color: #004699; text-decoration: none; font-weight: 600; }
        .link-login a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="login-container">
        <img src="https://intra.kemendag.go.id/res/assets/img/logo/logo-kemendag-horizontal-white.svg" alt="Logo Kementrian Perdagangan" class="logo">

        <div class="login-form-wrapper">
            <h1>Daftar Akun Baru</h1>
            <p>Silahkan buat akun untuk mengakses sistem</p>

            <?php if($pesan): ?>
                <div class="alert alert-error"><?= $pesan ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label>Email / NIP / Username</label>
                    <input type="text" name="email" required placeholder="Contoh: user123">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter">
                        <span id="togglePassword" class="password-toggle">lihat</span>
                    </div>
                </div>

                <button type="submit" class="btn">Buat Akun Sekarang</button>
            </form>

            <div class="link-login">
                Sudah punya akun? <a href="login.php">Masuk disini</a>
            </div>
        </div>

        <div class="footer-text">
            © 2025 Pusat Data dan Sistem Informasi Kementerian Perdagangan RI
        </div>
    </div> 

    <script>
        // Script intip password
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? 'lihat' : 'sembunyi';
        });
    </script>

</body>
</html>