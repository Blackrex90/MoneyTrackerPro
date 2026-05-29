<?php
// =====================================================
// MoneyTracker Pro - Database Connection Configuration
// =====================================================

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'moneytrackerpro');

// PDO Connection
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}

// Function untuk format currency
function formatCurrency($value) {
    return 'Rp ' . number_format($value, 0, ',', '.');
}

// Function untuk format tanggal
function formatTanggal($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Function untuk validasi input
function validateInput($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

// Function untuk cek session
function checkSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
?>