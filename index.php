<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MoneyTracker Pro - Dashboard Keuangan Premium Modern">
    <meta name="theme-color" content="#4f46e5">
    <title>MoneyTracker Pro - Dashboard Keuangan</title>
    
    <!-- Bootstrap 5.3.8 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    
    <!-- Custom CSS (Design System) -->
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/effects.css">
</head>
<body class="light-mode">
    <!-- ===== Top Navigation Bar ===== -->
    <nav class="navbar">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold slide-in-left" href="index.php" title="Dashboard">
                <i class="bi bi-wallet2"></i>
                <span>MoneyTracker Pro</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav">
                <i class="bi bi-list"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <!-- Theme Toggle -->
                    <li class="nav-item">
                        <button class="btn btn-light btn-sm me-2" id="darkModeToggle" title="Toggle Dark Mode" aria-label="Toggle Dark Mode">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </li>
                    
                    <!-- Export Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" title="Export">
                            <i class="bi bi-download"></i> Export
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" onclick="exportPDF()"><i class="bi bi-filetype-pdf"></i> PDF</a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportExcel()"><i class="bi bi-filetype-xlsx"></i> Excel</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="printLaporan()"><i class="bi bi-printer"></i> Print</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ===== Main Container ===== -->
    <div class="container-fluid" style="padding: var(--layout-container-padding);">
        <!-- Alert Container -->
        <div id="alertContainer" class="fade-in"></div>

        <!-- ===== Dashboard Header ===== -->
        <div class="row mb-4 slide-in-up">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h1 class="mb-1">
                            <i class="bi bi-speedometer2 gradient-text"></i>
                            Dashboard Keuangan
                        </h1>
                        <p class="text-muted mb-0">Kelola keuangan Anda dengan mudah dan transparan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== Stat Cards ===== -->
        <div class="row mb-4 g-3">
            <!-- Total Saldo -->
            <div class="col-lg-3 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.1s;">
                <div class="card stat-card hover-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-2">
                                    <i class="bi bi-wallet"></i> Total Saldo
                                </h6>
                                <h3 class="mb-2 gradient-text" id="totalSaldo">Rp 0</h3>
                            </div>
                            <div class="stat-icon bg-primary hover-glow">
                                <i class="bi bi-wallet-fill"></i>
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> Update real-time
                        </small>
                    </div>
                </div>
            </div>

            <!-- Total Pemasukan -->
            <div class="col-lg-3 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.2s;">
                <div class="card stat-card hover-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-2">
                                    <i class="bi bi-arrow-up-circle"></i> Pemasukan
                                </h6>
                                <h3 class="mb-2 text-success" id="totalPemasukan">Rp 0</h3>
                            </div>
                            <div class="stat-icon bg-success hover-glow">
                                <i class="bi bi-arrow-up-circle-fill"></i>
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> Semua waktu
                        </small>
                    </div>
                </div>
            </div>

            <!-- Total Pengeluaran -->
            <div class="col-lg-3 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.3s;">
                <div class="card stat-card hover-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-2">
                                    <i class="bi bi-arrow-down-circle"></i> Pengeluaran
                                </h6>
                                <h3 class="mb-2 text-danger" id="totalPengeluaran">Rp 0</h3>
                            </div>
                            <div class="stat-icon bg-danger hover-glow">
                                <i class="bi bi-arrow-down-circle-fill"></i>
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> Semua waktu
                        </small>
                    </div>
                </div>
            </div>

            <!-- Total Transaksi -->
            <div class="col-lg-3 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.4s;">
                <div class="card stat-card hover-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-2">
                                    <i class="bi bi-graph-up"></i> Transaksi
                                </h6>
                                <h3 class="mb-2 text-info" id="totalTransaksi">0</h3>
                            </div>
                            <div class="stat-icon bg-info hover-glow">
                                <i class="bi bi-graph-up"></i>
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-list-check"></i> Total
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== Charts & Quick Actions ===== -->
        <div class="row mb-4 g-3">
            <!-- Chart Section -->
            <div class="col-lg-8 fade-in-up">
                <div class="card card-hover-enhanced">
                    <div class="card-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-bar-chart-line gradient-text"></i>
                            <h5 class="mb-0">Grafik Pemasukan vs Pengeluaran</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="chartKeuangan" height="80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="col-lg-4 fade-in-up">
                <div class="card card-hover-enhanced">
                    <div class="card-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-lightning-fill gradient-text"></i>
                            <h5 class="mb-0">Aksi Cepat</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Add Transaction Buttons -->
                        <div class="d-grid gap-2 mb-3">
                            <button class="btn btn-success btn-lg hover-scale ripple" data-bs-toggle="modal" data-bs-target="#modalTransaksi" onclick="resetForm()">
                                <i class="bi bi-plus-circle"></i> Pemasukan Baru
                            </button>
                            <button class="btn btn-danger btn-lg hover-scale ripple" data-bs-toggle="modal" data-bs-target="#modalTransaksi" onclick="resetForm('pengeluaran')">
                                <i class="bi bi-dash-circle"></i> Pengeluaran Baru
                            </button>
                        </div>

                        <hr class="my-3">

                        <!-- Filter Section -->
                        <h6 class="mb-3">
                            <i class="bi bi-funnel"></i> Filter Transaksi
                        </h6>

                        <div class="form-group mb-3">
                            <label class="form-label">Jenis Transaksi</label>
                            <select class="form-select form-select-sm" id="filterJenis">
                                <option value="">Semua</option>
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" id="filterTanggalMulai">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" id="filterTanggalAkhir">
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-sm" onclick="applyFilter()">
                                <i class="bi bi-search"></i> Terapkan Filter
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="resetFilter()">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== Transaction Table ===== -->
        <div class="row fade-in-up">
            <div class="col-12">
                <div class="card card-hover-enhanced">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-table gradient-text"></i>
                                <h5 class="mb-0">Daftar Transaksi</h5>
                            </div>
                            <input type="text" class="form-control form-control-sm input-focus" id="searchTransaksi" placeholder="Cari transaksi..." style="max-width: 300px;">
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Table Container -->
                        <div id="tableContainer" class="table-responsive">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="text-muted">Memuat data transaksi...</p>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <nav aria-label="Pagination" class="mt-4">
                            <ul class="pagination justify-content-center" id="pagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Modal: Add/Edit Transaction ===== -->
    <div class="modal fade" id="modalTransaksi" tabindex="-1" backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="bi bi-plus-circle"></i> Tambah Transaksi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form id="formTransaksi" onsubmit="saveTransaksi(event)" novalidate>
                    <div class="modal-body">
                        <input type="hidden" id="transaksiId">

                        <!-- Jenis Transaksi -->
                        <div class="form-group mb-3">
                            <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenisTransaksi" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                            <div class="invalid-feedback">Jenis transaksi harus dipilih</div>
                        </div>

                        <!-- Tanggal -->
                        <div class="form-group mb-3">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal" required>
                            <div class="invalid-feedback">Tanggal harus diisi</div>
                        </div>

                        <!-- Kategori -->
                        <div class="form-group mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kategori" placeholder="Contoh: Gaji, Belanja, Makan" required>
                            <div class="invalid-feedback">Kategori harus diisi</div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                        </div>

                        <!-- Nominal -->
                        <div class="form-group mb-3">
                            <label class="form-label">Nominal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nominal" placeholder="Rp 0" required oninput="formatCurrencyInput(this)">
                            <small class="form-text">Masukkan angka, akan otomatis diformat</small>
                            <div class="invalid-feedback">Nominal harus diisi dengan benar</div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="form-group mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="metode">
                                <option value="">-- Pilih Metode --</option>
                                <option value="Tunai">Tunai</option>
                                <option value="Debit Card">Debit Card</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="E-Wallet">E-Wallet</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== Modal: Transaction Detail ===== -->
    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-info-circle"></i> Detail Transaksi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
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

    <!-- ===== Scripts ===== -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
</body>
</html>
