<?php
// api_spbu.php
header('Content-Type: application/json');
require 'db.php'; 

// Cek param aksi: 'list' untuk ambil semua, default untuk cari detail
$action = isset($_GET['action']) ? $_GET['action'] : 'detail';

try {
    if ($action === 'list') {
        // --- MODE 1: AMBIL SEMUA NOMOR (Untuk Dropdown/Datalist) ---
        $stmt = $pdo->query("SELECT nomor_spbu FROM data_spbu ORDER BY nomor_spbu ASC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);
    
    } else {
        // --- MODE 2: CARI DETAIL (Untuk Autofill Alamat) ---
        $nomor = isset($_GET['nomor']) ? trim($_GET['nomor']) : '';
        
        if ($nomor) {
            $stmt = $pdo->prepare("SELECT * FROM data_spbu WHERE nomor_spbu = :n LIMIT 1");
            $stmt->execute(['n' => $nomor]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            }
        } else {
            echo json_encode(['status' => 'empty', 'message' => 'Parameter nomor kosong']);
        }
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>