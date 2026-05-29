<?php
// =====================================================
// MoneyTracker Pro - Process Tambah Transaksi
// =====================================================

header('Content-Type: application/json');
require_once __DIR__ . '/../config/koneksi.php';

$response = [
    'success' => false,
    'message' => 'Terjadi kesalahan'
];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method tidak diizinkan');
    }

    // Validasi input
    $tanggal = validateInput($_POST['tanggal'] ?? '');
    $jenis_transaksi = validateInput($_POST['jenis_transaksi'] ?? '');
    $kategori = validateInput($_POST['kategori'] ?? '');
    $deskripsi = validateInput($_POST['deskripsi'] ?? '');
    $nominal = isset($_POST['nominal']) ? str_replace(['Rp', '.', ' '], '', $_POST['nominal']) : 0;
    $metode_pembayaran = validateInput($_POST['metode_pembayaran'] ?? '');

    // Validasi data required
    if (empty($tanggal) || empty($jenis_transaksi) || empty($kategori) || empty($nominal)) {
        throw new Exception('Semua field required harus diisi');
    }

    // Validasi nominal
    if ($nominal <= 0 || !is_numeric($nominal)) {
        throw new Exception('Nominal harus lebih dari 0');
    }

    // Validasi jenis transaksi
    if (!in_array($jenis_transaksi, ['pemasukan', 'pengeluaran'])) {
        throw new Exception('Jenis transaksi tidak valid');
    }

    // Insert ke database
    $sql = "INSERT INTO transaksi (tanggal, jenis_transaksi, kategori, deskripsi, nominal, metode_pembayaran) 
            VALUES (:tanggal, :jenis_transaksi, :kategori, :deskripsi, :nominal, :metode_pembayaran)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':tanggal' => $tanggal,
        ':jenis_transaksi' => $jenis_transaksi,
        ':kategori' => $kategori,
        ':deskripsi' => $deskripsi,
        ':nominal' => $nominal,
        ':metode_pembayaran' => $metode_pembayaran
    ]);

    $response['success'] = true;
    $response['message'] = 'Transaksi berhasil ditambahkan';
    $response['id'] = $pdo->lastInsertId();

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>