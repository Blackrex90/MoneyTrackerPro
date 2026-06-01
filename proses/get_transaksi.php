<?php
// =====================================================
// MoneyTracker Pro - Get Transactions API (Enhanced Security)
// =====================================================

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/security.php';

try {
    // Rate limiting check
    $clientIP = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    if (isRateLimited('get_transaksi_' . $clientIP, 30, 300)) {
        throw new Exception('Terlalu banyak permintaan');
    }

    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method tidak diizinkan');
    }

    // Query transactions with security
    $sql = "SELECT 
            id,
            DATE_FORMAT(tanggal, '%Y-%m-%d') as tanggal,
            jenis_transaksi,
            kategori,
            deskripsi,
            nominal,
            metode_pembayaran,
            created_at,
            updated_at
            FROM transaksi 
            ORDER BY tanggal DESC, id DESC 
            LIMIT 10000";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sanitize output
    $transaksi = array_map(function($item) {
        return [
            'id' => (int) $item['id'],
            'tanggal' => $item['tanggal'],
            'jenis_transaksi' => escapeOutput($item['jenis_transaksi']),
            'kategori' => escapeOutput($item['kategori']),
            'deskripsi' => escapeOutput($item['deskripsi']),
            'nominal' => (int) $item['nominal'],
            'metode_pembayaran' => escapeOutput($item['metode_pembayaran'])
        ];
    }, $transaksi);

    echo json_encode($transaksi, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    header('HTTP/1.1 400 Bad Request');
    
    logSecurityEvent('get_transaksi_error', [
        'error' => $e->getMessage(),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);
    
    echo json_encode([
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

exit;
?>
