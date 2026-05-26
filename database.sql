-- =====================================================
-- MoneyTracker Pro - Database Schema
-- =====================================================

-- Buat database
CREATE DATABASE IF NOT EXISTS moneytrackerpro;
USE moneytrackerpro;

-- =====================================================
-- Tabel Transaksi
-- =====================================================
CREATE TABLE IF NOT EXISTS transaksi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tanggal DATE NOT NULL,
    jenis_transaksi ENUM('pemasukan', 'pengeluaran') NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    deskripsi VARCHAR(255),
    nominal DECIMAL(15, 2) NOT NULL,
    metode_pembayaran VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tanggal (tanggal),
    INDEX idx_jenis (jenis_transaksi),
    INDEX idx_kategori (kategori)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Sample Data
-- =====================================================

-- Data Pemasukan
INSERT INTO transaksi (tanggal, jenis_transaksi, kategori, deskripsi, nominal, metode_pembayaran) VALUES
('2026-05-26', 'pemasukan', 'Gaji', 'Gaji Bulanan Mei', 5000000, 'Transfer Bank'),
('2026-05-25', 'pemasukan', 'Bonus', 'Bonus Proyeksi', 1500000, 'Transfer Bank'),
('2026-05-24', 'pemasukan', 'Freelance', 'Proyek Website', 2500000, 'Transfer Bank'),
('2026-05-23', 'pemasukan', 'Investasi', 'Return Dividen', 800000, 'Transfer Bank'),
('2026-05-22', 'pemasukan', 'Lain-lain', 'Refund Barang', 350000, 'Tunai');

-- Data Pengeluaran
INSERT INTO transaksi (tanggal, jenis_transaksi, kategori, deskripsi, nominal, metode_pembayaran) VALUES
('2026-05-26', 'pengeluaran', 'Makanan', 'Sarapan Pagi', 50000, 'Tunai'),
('2026-05-26', 'pengeluaran', 'Transportasi', 'Bensin Mobil', 150000, 'Debit Card'),
('2026-05-25', 'pengeluaran', 'Listrik & Air', 'Tagihan Listrik', 450000, 'Transfer Bank'),
('2026-05-25', 'pengeluaran', 'Internet', 'Tagihan WiFi', 300000, 'Transfer Bank'),
('2026-05-24', 'pengeluaran', 'Belanja', 'Kebutuhan Rumah', 750000, 'Debit Card'),
('2026-05-24', 'pengeluaran', 'Hiburan', 'Bioskop Keluarga', 200000, 'Tunai'),
('2026-05-23', 'pengeluaran', 'Kesehatan', 'Konsultasi Dokter', 300000, 'Transfer Bank'),
('2026-05-23', 'pengeluaran', 'Pendidikan', 'Kursus Online', 500000, 'Transfer Bank'),
('2026-05-22', 'pengeluaran', 'Cicilan', 'Cicilan Mobil', 2000000, 'Transfer Bank'),
('2026-05-22', 'pengeluaran', 'Asuransi', 'Premi Asuransi', 400000, 'Transfer Bank');

-- =====================================================
-- Akhir Database Schema
-- =====================================================
