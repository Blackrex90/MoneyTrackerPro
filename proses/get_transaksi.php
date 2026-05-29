<?php
// =====================================================
// MoneyTracker Pro - Get Transaksi API
// =====================================================

header('Content-Type: application/json');
require_once __DIR__ . '/../config/koneksi.php';

try {
    $sql = "SELECT * FROM transaksi ORDER BY tanggal DESC, id DESC";
    $stmt = $pdo->query($sql);
    $transaksi = $stmt->fetchAll();

    echo json_encode($transaksi);
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
?>
