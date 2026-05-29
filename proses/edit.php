<?php
// =====================================================
// MoneyTracker Pro - Process Edit Transaksi
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
    $id = (int)($_POST['id'] ?? 0);
    $tanggal = validateInput($_POST['tanggal'] ?? '');
    $jenis_transaksi = validateInput($_POST['jenis_transaksi'] ?? '');
    $kategori = validateInput($_POST['kategori'] ?? '');
    $deskripsi = validateInput($_POST['deskripsi'] ?? '');
    $nominal = isset($_POST['nominal']) ? str_replace(['Rp', '.', ' '], '', $_POST['nominal']) : 0;
    $metode_pembayaran = validateInput($_POST['metode_pembayaran'] ?? '');

    // Validasi ID
    if ($id <= 0) {
        throw new Exception('ID transaksi tidak valid');
    }

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

    // Cek transaksi ada
    $stmt = $pdo->prepare('SELECT id FROM transaksi WHERE id = :id');
    $stmt->execute([':id' => $id]);
    if (!$stmt->fetch()) {
        throw new Exception('Transaksi tidak ditemukan');
    }

    // Update database
    $sql = "UPDATE transaksi SET 
            tanggal = :tanggal,
            jenis_transaksi = :jenis_transaksi,
            kategori = :kategori,
            deskripsi = :deskripsi,
            nominal = :nominal,
            metode_pembayaran = :metode_pembayaran,
            updated_at = CURRENT_TIMESTAMP
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':tanggal' => $tanggal,
        ':jenis_transaksi' => $jenis_transaksi,
        ':kategori' => $kategori,
        ':deskripsi' => $deskripsi,
        ':nominal' => $nominal,
        ':metode_pembayaran' => $metode_pembayaran
    ]);

    $response['success'] = true;
    $response['message'] = 'Transaksi berhasil diperbarui';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>