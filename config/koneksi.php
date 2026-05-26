<?php
/**
 * MoneyTracker Pro - Database Connection
 * Menggunakan PDO untuk keamanan maksimal
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'moneytracker_pro');
define('DB_PORT', 3306);

// Timezone
date_default_timezone_set('Asia/Jakarta');

try {
    // Buat koneksi PDO
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // Jika koneksi gagal
    die(json_encode([
        'status' => 'error',
        'message' => 'Koneksi database gagal: ' . $e->getMessage()
    ]));
}

/**
 * Helper Function - Format Rupiah
 */
function formatRupiah($nominal) {
    return 'Rp ' . number_format($nominal, 0, ',', '.');
}

/**
 * Helper Function - Format Tanggal
 */
function formatTanggal($tanggal) {
    $bulan = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    );
    
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

/**
 * Helper Function - Sanitasi Input
 */
function sanitasi($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Helper Function - Response JSON
 */
function jsonResponse($status, $message, $data = null) {
    return json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
}
?>