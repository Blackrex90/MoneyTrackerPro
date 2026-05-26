-- =====================================================
-- MoneyTracker Pro Database Schema
-- =====================================================

-- Drop Database jika sudah ada
DROP DATABASE IF EXISTS `moneytracker_pro`;

-- Create Database
CREATE DATABASE IF NOT EXISTS `moneytracker_pro`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Gunakan Database
USE `moneytracker_pro`;

-- =====================================================
-- Tabel Transaksi
-- =====================================================

CREATE TABLE `transaksi` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID Transaksi',
  `tanggal` DATE NOT NULL COMMENT 'Tanggal Transaksi',
  `jenis_transaksi` ENUM('pemasukan', 'pengeluaran') NOT NULL COMMENT 'Jenis Transaksi',
  `kategori` VARCHAR(100) NOT NULL COMMENT 'Kategori Transaksi',
  `deskripsi` VARCHAR(255) NOT NULL COMMENT 'Deskripsi Transaksi',
  `nominal` DECIMAL(15, 2) NOT NULL COMMENT 'Nominal Transaksi',
  `metode_pembayaran` VARCHAR(50) NOT NULL COMMENT 'Metode Pembayaran',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu Dibuat',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu Diupdate',
  INDEX `idx_tanggal` (`tanggal`),
  INDEX `idx_jenis` (`jenis_transaksi`),
  INDEX `idx_kategori` (`kategori`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Transaksi Keuangan';

-- =====================================================
-- Sample Data
-- =====================================================

INSERT INTO `transaksi` (
  `tanggal`, `jenis_transaksi`, `kategori`, `deskripsi`, `nominal`, `metode_pembayaran`
) VALUES
('2026-05-26', 'pemasukan', 'Gaji', 'Gaji Bulan Mei', 5000000, 'Transfer Bank'),
('2026-05-26', 'pemasukan', 'Freelance', 'Project Website', 2000000, 'E-Wallet'),
('2026-05-25', 'pengeluaran', 'Makan', 'Makan Siang', 75000, 'Cash'),
('2026-05-25', 'pengeluaran', 'Transportasi', 'Bensin Motor', 100000, 'Debit Card'),
('2026-05-24', 'pengeluaran', 'Utilitas', 'Listrik Rumah', 450000, 'Transfer Bank'),
('2026-05-24', 'pemasukan', 'Bonus', 'Bonus Performance', 1500000, 'Transfer Bank'),
('2026-05-23', 'pengeluaran', 'Belanja', 'Belanja Bulanan', 800000, 'Debit Card'),
('2026-05-22', 'pengeluaran', 'Makan', 'Makan Malam', 120000, 'Cash'),
('2026-05-21', 'pemasukan', 'Investasi', 'Dividen Investasi', 500000, 'Transfer Bank'),
('2026-05-20', 'pengeluaran', 'Kesehatan', 'Pemeriksaan Dokter', 250000, 'Debit Card');

-- =====================================================
-- Query untuk Statistik
-- =====================================================

-- Total Saldo
-- SELECT 
--   (SELECT SUM(nominal) FROM transaksi WHERE jenis_transaksi = 'pemasukan') -
--   (SELECT SUM(nominal) FROM transaksi WHERE jenis_transaksi = 'pengeluaran') as total_saldo;

-- Total Pemasukan Hari Ini
-- SELECT SUM(nominal) as total FROM transaksi 
-- WHERE jenis_transaksi = 'pemasukan' AND DATE(tanggal) = CURDATE();

-- Total Pengeluaran Hari Ini
-- SELECT SUM(nominal) as total FROM transaksi 
-- WHERE jenis_transaksi = 'pengeluaran' AND DATE(tanggal) = CURDATE();