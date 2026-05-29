<?php
// =====================================================
// MoneyTracker Pro - Transaksi Page
// =====================================================

require_once 'config/koneksi.php';
$page_title = 'Daftar Transaksi';
?>
<?php include 'partials/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-9">
            <h2 class="mb-0"><i class="bi bi-list-check"></i> Daftar Transaksi Lengkap</h2>
        </div>
        <div class="col-md-3 text-end">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTransaksi" onclick="resetForm()">
                <i class="bi bi-plus-lg"></i> Tambah Transaksi
            </button>
        </div>
    </div>

    <div id="alertContainer"></div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-table"></i> Tabel Transaksi</h5>
                    <input type="text" class="form-control form-control-sm" id="searchTransaksi" placeholder="Cari transaksi..." style="max-width: 250px;">
                </div>
                <div class="card-body">
                    <div id="tableContainer">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <nav aria-label="Pagination" class="mt-4">
                        <ul class="pagination justify-content-center" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Transaksi -->
<div class="modal fade" id="modalTransaksi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTransaksi" onsubmit="saveTransaksi(event)">
                <div class="modal-body">
                    <input type="hidden" id="transaksiId">

                    <div class="mb-3">
                        <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenisTransaksi" required>
                            <option value="">Pilih Jenis</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kategori" placeholder="Contoh: Gaji, Belanja, dll" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" rows="2" placeholder="Keterangan tambahan..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nominal <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nominal" placeholder="Rp 0" required oninput="formatCurrencyInput(this)">
                        <small class="text-muted">Hanya angka, akan otomatis diformat</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="metode">
                            <option value="">Pilih Metode</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Debit Card">Debit Card</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="E-Wallet">E-Wallet</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Transaksi -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" id="btnEdit" onclick="editTransaksiFromDetail()">
                    <i class="bi bi-pencil"></i> Edit
                </button>
                <button type="button" class="btn btn-danger" id="btnHapus" onclick="hapusTransaksiFromDetail()">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
