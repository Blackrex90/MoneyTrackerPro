<?php
// =====================================================
// MoneyTracker Pro - Process Hapus Transaksi
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

    if ($id <= 0) {
        throw new Exception('ID transaksi tidak valid');
    }

    // Cek transaksi ada
    $stmt = $pdo->prepare('SELECT id FROM transaksi WHERE id = :id');
    $stmt->execute([':id' => $id]);
    if (!$stmt->fetch()) {
        throw new Exception('Transaksi tidak ditemukan');
    }

    // Hapus dari database
    $stmt = $pdo->prepare('DELETE FROM transaksi WHERE id = :id');
    $stmt->execute([':id' => $id]);

    $response['success'] = true;
    $response['message'] = 'Transaksi berhasil dihapus';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>