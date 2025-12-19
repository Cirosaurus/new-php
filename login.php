<?php
session_start();
require 'db.php';

// 1. Kalau sudah login, langsung lempar ke index (Home)
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

// 2. Proses saat tombol "Masuk Sekarang" ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Di HTML nama inputnya 'email', tapi kita pakai sebagai username di DB
    $username_input = $_POST['email'];
    $password_input = $_POST['password'];

    // Cek User di Database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u");
    $stmt->execute(['u' => $username_input]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek Password
    if ($user && password_verify($password_input, $user['password'])) {
        // SUKSES!
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Email/NIP atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Intra Kemendag</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="https://intra.kemendag.go.id/favicon.ico" />

    <style>
        /* --- CSS ASLI DARI ANDA --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        html {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }

        .login-container {
            display: flex;
            width: 100%;
            height: 100vh;
            overflow: auto;
            background-color: #004699;
            padding: 48px;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://intra.kemendag.go.id/res/assets/img/pages/publik/auth/bg-2025.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.1;
            z-index: 1;
        }

        .logo {
            position: relative;
            z-index: 2;
            width: 200px;
            margin-bottom: 40px;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 2;
            background-color: #ffffff;
            padding: 32px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .login-form-wrapper h1 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            min-height: 1.2em;
        }

        .login-form-wrapper h1::after {
            content: '|';
            margin-left: 5px;
            opacity: 1;
            animation: blink 0.7s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .login-form-wrapper p {
            font-size: 14px;
            color: #666;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            border: 1px solid #d9d9d9;
            border-radius: 6px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #004699;
            box-shadow: 0 0 0 2px rgba(0, 70, 153, 0.2);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 14px;
            color: #888;
            font-weight: 600;
            user-select: none;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            color: #555;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 8px;
            accent-color: #004699;
        }

        .form-options a {
            color: #004699;
            text-decoration: none;
            font-weight: 500;
        }

        .form-options a:hover {
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-primary {
            background-color: #004699;
            color: white;
        }

        .btn-primary:hover {
            background-color: #003a7d;
        }

        footer {
            text-align: center;
            margin-top: 48px;
            font-size: 12px;
            color: #999;
        }

        /* Tambahan CSS untuk Pesan Error */
        .alert-error {
            background-color: #ffebe9;
            color: #cf222e;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #ffc1bc;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <img src="https://intra.kemendag.go.id/res/assets/img/logo/logo-kemendag-horizontal-white.svg" alt="Logo Kementrian Perdagangan" class="logo">

        <div class="login-form-wrapper">
            <h1></h1>
            <p>Silahkan masukkan e-mail/NIP/ID dan kata sandi anda di halaman Masuk Akun untuk masuk ke sistem Intra Kemendag RI</p>

            <?php if (!empty($error)): ?>
                <div class="alert-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="email">Email / NIP / Alias *</label>
                    <input type="text" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span id="togglePassword" class="password-toggle">lihat</span>
                    </div>
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat Saya</label>
                    </div>
                    <a href="#">Lupa Password?</a>
                </div>

                <div style="text-align: center; margin-top: 20px; font-size: 14px;">
                    Belum punya akun? <a href="register.php" style="color: #004699; font-weight: bold; text-decoration: none;">Daftar dulu</a>
                </div>

                <button type="submit" class="btn btn-primary">Masuk Sekarang</button>
            </form>

            <footer>© 2025 Pusat Data dan Sistem Informasi Kementerian Perdagangan RI</footer>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? 'lihat' : 'sembunyi';
        });

        // EFEK TYPEWRITER
        const h1 = document.querySelector('.login-form-wrapper h1');
        const words = ["Selamat Datang", "Welcome", "ようこそ"];
        let wordIndex = 0,
            charIndex = 0,
            isDeleting = false;
        const typeSpeed = 150,
            deleteSpeed = 75,
            pauseSpeed = 2000;

        function type() {
            const currentWord = words[wordIndex];
            let typeDelay = typeSpeed;
            if (isDeleting) {
                h1.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
                typeDelay = deleteSpeed;
            } else {
                h1.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
            }
            if (!isDeleting && charIndex === currentWord.length) {
                typeDelay = pauseSpeed;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                typeDelay = 500;
            }
            setTimeout(type, typeDelay);
        }
        document.addEventListener('DOMContentLoaded', type);
    </script>
</body>

</html>