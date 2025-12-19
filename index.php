<!DOCTYPE html>
<html>
<head>
    <title>Tes PHP + PostgreSQL</title>
    <style>body { font-family: sans-serif; padding: 20px; }</style>
</head>
<body>
    <h1>Data dari Supabase</h1>

    <?php
    // Mengambil kredensial dari Environment Variable Render
    $host = getenv('db.cjnmxzeteyravdzkizwc.supabase.co');
    $db   = getenv('postgres');
    $user = getenv('postgres');
    $pass = getenv('monitortengahasik');
    $port = getenv('5432');

    $dsn = "pgsql:host=$host;port=$port;dbname=$db";

    try {
        // Membuat koneksi PDO
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        // Query sederhana mengambil data
        $stmt = $pdo->query("SELECT * FROM tamu");
        
        echo "<ul>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li><strong>" . htmlspecialchars($row['nama']) . ":</strong> " . htmlspecialchars($row['pesan']) . "</li>";
        }
        echo "</ul>";

    } catch (PDOException $e) {
        echo "<p style='color:red'>Gagal koneksi: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>