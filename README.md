# MoneyTracker Pro

**MoneyTracker Pro** adalah aplikasi pembukuan uang masuk dan uang keluar berbasis web yang dibangun dengan **PHP Native**, **MySQL**, **Bootstrap 5.3.8**, **HTML**, **CSS**, dan **JavaScript**.

Aplikasi ini dirancang untuk membantu pencatatan keuangan secara cepat, rapi, dan otomatis dengan tampilan dashboard modern, CRUD transaksi lengkap, perhitungan saldo real-time, serta laporan yang informatif.

---

## Fitur Utama

- Dashboard ringkasan keuangan
- CRUD transaksi lengkap
- Input uang masuk dan uang keluar
- Perhitungan otomatis saldo
- Riwayat transaksi lengkap
- Pencarian dan filter transaksi
- Edit dan hapus transaksi
- Statistik pemasukan dan pengeluaran
- Grafik keuangan sederhana
- Format mata uang Rupiah
- Validasi form di sisi client dan server
- Desain modern dan responsif
- Siap dikembangkan ke fitur export PDF / Excel
- Struktur kode rapi dan mudah dipelihara

---

## Teknologi yang Digunakan

- **PHP Native**
- **MySQL**
- **Bootstrap 5.3.8**
- **HTML5**
- **CSS3**
- **Vanilla JavaScript**
- **PDO Prepared Statements**

---

## Tujuan Aplikasi

Aplikasi ini dibuat untuk:

- pencatatan keuangan pribadi
- pembukuan UMKM
- monitoring pemasukan dan pengeluaran
- membantu melihat kondisi saldo secara cepat
- memudahkan rekap transaksi harian, mingguan, bulanan, dan tahunan

---

## Tampilan Aplikasi

### Komponen Utama
- Dashboard statistik
- Tabel transaksi
- Form tambah transaksi
- Form edit transaksi
- Modal konfirmasi hapus
- Notifikasi sukses / gagal
- Grafik pemasukan vs pengeluaran

### Desain
- Responsive di desktop, tablet, dan mobile
- Tampilan modern dan clean
- Menggunakan card, badge, table, modal, dan tombol Bootstrap
- Mendukung pengembangan dark mode

---

## Struktur Folder

```text
MoneyTrackerPro/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── script.js
├── config/
│   └── koneksi.php
├── partials/
│   ├── header.php
│   ├── sidebar.php
│   └── footer.php
├── proses/
│   ├── tambah.php
│   ├── edit.php
│   └── hapus.php
├── index.php
├── transaksi.php
├── laporan.php
├── export_pdf.php
├── export_excel.php
└── database.sql