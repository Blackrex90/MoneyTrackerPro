<?php

// Konfigurasi database
$host = 'localhost';
$db_name = 'moneytrackerpro';
$username = 'root';
$password = '';

try {
    // Koneksi ke database menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

?>