/* =====================================================
   MoneyTracker Pro - Enhanced JavaScript & Interactions
   ===================================================== */

'use strict';

// ===== Global Variables =====
let allTransaksi = [];
let currentPage = 1;
let itemsPerPage = 10;
let filteredTransaksi = [];
let chartInstance = null;
const DEBOUNCE_DELAY = 300;

// ===== Initialize on Document Ready =====
document.addEventListener('DOMContentLoaded', function() {
    loadThemePreference();
    setupEventListeners();
    loadTransaksi();
    setupThemeToggle();
});

// ===== Theme Management =====
function setupThemeToggle() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (!darkModeToggle) return;

    darkModeToggle.addEventListener('click', function() {
        toggleTheme();
    });

    // Set initial icon based on current theme
    updateThemeIcon();
}

function toggleTheme() {
    const body = document.body;
    const isDarkMode = body.classList.toggle('dark-mode');
    body.classList.toggle('light-mode', !isDarkMode);
    
    localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
    updateThemeIcon();
    updateChartTheme();
    
    // Trigger smooth transition
    showAlert('success', `Mode ${isDarkMode ? 'Gelap' : 'Terang'} Aktif`);
}

function loadThemePreference() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    const body = document.body;
    
    body.classList.remove('light-mode', 'dark-mode');
    body.classList.add(savedTheme + '-mode');
}

function updateThemeIcon() {
    const toggle = document.getElementById('darkModeToggle');
    if (!toggle) return;
    
    const isDark = document.body.classList.contains('dark-mode');
    toggle.innerHTML = isDark 
        ? '<i class="bi bi-sun-fill"></i>' 
        : '<i class="bi bi-moon-stars"></i>';
    toggle.title = isDark ? 'Light Mode' : 'Dark Mode';
}

// ===== Event Listeners Setup =====
function setupEventListeners() {
    // Search
    const searchInput = document.getElementById('searchTransaksi');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleSearch, DEBOUNCE_DELAY));
    }

    // Filter
    const filterJenis = document.getElementById('filterJenis');
    if (filterJenis) {
        filterJenis.addEventListener('change', applyFilter);
    }
}

// ===== Debounce Function =====
function debounce(func, delay) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
}

// ===== Data Loading =====
function loadTransaksi() {
    const container = document.getElementById('tableContainer');
    if (container) {
        showLoadingState(container);
    }

    fetch('proses/get_transaksi.php')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            // Check if error
            if (data.error) {
                throw new Error(data.error);
            }

            allTransaksi = Array.isArray(data) ? data : [];
            filteredTransaksi = [...allTransaksi];
            currentPage = 1;
            
            displayTable();
            updateStatistics();
            initializeCharts();
        })
        .catch(error => {
            console.error('Error loading transactions:', error);
            showAlert('danger', 'Gagal memuat data transaksi: ' + error.message);
            showEmptyState(container);
        });
}

function showLoadingState(container) {
    container.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted">Memuat data transaksi...</p>
        </div>
    `;
}

function showEmptyState(container) {
    container.innerHTML = `
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h6 class="mt-3">Tidak Ada Data</h6>
            <p class="text-muted">Belum ada transaksi. Mulai dengan membuat transaksi baru.</p>
        </div>
    `;
}

// ===== Display Table =====
function displayTable() {
    const container = document.getElementById('tableContainer');
    if (!container) return;

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginatedData = filteredTransaksi.slice(start, end);

    if (paginatedData.length === 0) {
        showEmptyState(container);
        document.getElementById('pagination').innerHTML = '';
        return;
    }

    let html = `
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th class="text-end">Nominal</th>
                    <th>Metode</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
    `;

    paginatedData.forEach((item, index) => {
        const badgeClass = item.jenis_transaksi === 'pemasukan' 
            ? 'badge-pemasukan' 
            : 'badge-pengeluaran';
        const nominal = formatCurrency(item.nominal);
        const tanggal = formatDate(item.tanggal);

        html += `
            <tr class="fade-in-up" style="animation-delay: ${index * 0.05}s;">
                <td>${tanggal}</td>
                <td>
                    <span class="badge ${badgeClass}">
                        ${item.jenis_transaksi === 'pemasukan' 
                            ? '<i class="bi bi-arrow-up"></i> Pemasukan' 
                            : '<i class="bi bi-arrow-down"></i> Pengeluaran'}
                    </span>
                </td>
                <td>${escapeHtml(item.kategori)}</td>
                <td><small>${escapeHtml(item.deskripsi || '-')}</small></td>
                <td class="text-end fw-bold ${item.jenis_transaksi === 'pemasukan' ? 'text-success' : 'text-danger'}">
                    ${nominal}
                </td>
                <td><small>${escapeHtml(item.metode_pembayaran || '-')}</small></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-info me-1 hover-scale" onclick="viewDetail(${item.id})" 
                            title="Lihat Detail" data-bs-toggle="tooltip">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning me-1 hover-scale" onclick="editTransaksi(${item.id})" 
                            title="Edit" data-bs-toggle="tooltip">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger hover-scale" onclick="deleteTransaksi(${item.id})" 
                            title="Hapus" data-bs-toggle="tooltip">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    html += `
            </tbody>
        </table>
    `;

    container.innerHTML = html;
    displayPagination();
}

// ===== Pagination =====
function displayPagination() {
    const pagination = document.getElementById('pagination');
    if (!pagination) return;

    const totalPages = Math.ceil(filteredTransaksi.length / itemsPerPage);
    pagination.innerHTML = '';

    if (totalPages <= 1) return;

    // Previous Button
    if (currentPage > 1) {
        pagination.innerHTML += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="goToPage(${currentPage - 1}); return false;">
                    <i class="bi bi-chevron-left"></i> Sebelumnya
                </a>
            </li>
        `;
    }

    // Page Numbers
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            pagination.innerHTML += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
        } else if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
            pagination.innerHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="goToPage(${i}); return false;">${i}</a>
                </li>
            `;
        } else if (i === currentPage - 2 || i === currentPage + 2) {
            pagination.innerHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }

    // Next Button
    if (currentPage < totalPages) {
        pagination.innerHTML += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="goToPage(${currentPage + 1}); return false;">
                    Selanjutnya <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        `;
    }
}

function goToPage(page) {
    currentPage = page;
    displayTable();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ===== Statistics Update =====
function updateStatistics() {
    const totalPemasukan = allTransaksi
        .filter(t => t.jenis_transaksi === 'pemasukan')
        .reduce((sum, t) => sum + parseFloat(t.nominal || 0), 0);

    const totalPengeluaran = allTransaksi
        .filter(t => t.jenis_transaksi === 'pengeluaran')
        .reduce((sum, t) => sum + parseFloat(t.nominal || 0), 0);

    const totalSaldo = totalPemasukan - totalPengeluaran;

    animateValue(document.getElementById('totalSaldo'), formatCurrency(totalSaldo));
    animateValue(document.getElementById('totalPemasukan'), formatCurrency(totalPemasukan));
    animateValue(document.getElementById('totalPengeluaran'), formatCurrency(totalPengeluaran));
    animateValue(document.getElementById('totalTransaksi'), allTransaksi.length);
}

function animateValue(element, value) {
    if (!element) return;
    element.classList.add('pulse');
    setTimeout(() => {
        element.textContent = value;
        element.classList.remove('pulse');
    }, 150);
}

// ===== Charts Management =====
function initializeCharts() {
    updateChart();
}

function updateChart() {
    const ctx = document.getElementById('chartKeuangan');
    if (!ctx) return;

    const monthlyData = {};
    allTransaksi.forEach(t => {
        const date = new Date(t.tanggal);
        const monthKey = date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short' });
        
        if (!monthlyData[monthKey]) {
            monthlyData[monthKey] = { pemasukan: 0, pengeluaran: 0 };
        }

        if (t.jenis_transaksi === 'pemasukan') {
            monthlyData[monthKey].pemasukan += parseFloat(t.nominal || 0);
        } else {
            monthlyData[monthKey].pengeluaran += parseFloat(t.nominal || 0);
        }
    });

    const labels = Object.keys(monthlyData);
    const pemasukanData = labels.map(label => monthlyData[label].pemasukan);
    const pengeluaranData = labels.map(label => monthlyData[label].pengeluaran);

    if (chartInstance) {
        chartInstance.destroy();
    }

    chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: pemasukanData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaranData,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
}

function updateChartTheme() {
    if (chartInstance) {
        updateChart();
    }
}

// ===== Form Management =====
function resetForm(jenis = '') {
    const form = document.getElementById('formTransaksi');
    if (form) form.reset();
    
    document.getElementById('transaksiId').value = '';
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-plus-circle"></i> Tambah Transaksi';
    
    if (jenis) {
        document.getElementById('jenisTransaksi').value = jenis;
    }
    
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal').value = today;
}

function saveTransaksi(event) {
    event.preventDefault();

    const form = document.getElementById('formTransaksi');
    if (!form.checkValidity()) {
        event.stopPropagation();
        form.classList.add('was-validated');
        return;
    }

    form.classList.remove('was-validated');

    const id = document.getElementById('transaksiId').value;
    const jenis = document.getElementById('jenisTransaksi').value;
    const tanggal = document.getElementById('tanggal').value;
    const kategori = document.getElementById('kategori').value;
    const deskripsi = document.getElementById('deskripsi').value;
    const nominal = document.getElementById('nominal').value;
    const metode = document.getElementById('metode').value;

    if (!jenis || !tanggal || !kategori || !nominal) {
        showAlert('danger', 'Semua field wajib diisi!');
        return;
    }

    const url = id ? 'proses/edit.php' : 'proses/tambah.php';
    const formData = new FormData();
    
    formData.append('id', id);
    formData.append('jenis_transaksi', jenis);
    formData.append('tanggal', tanggal);
    formData.append('kategori', kategori);
    formData.append('deskripsi', deskripsi);
    formData.append('nominal', nominal);
    formData.append('metode_pembayaran', metode);

    const button = event.target.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        button.disabled = false;
        button.innerHTML = originalText;

        if (data.success) {
            showAlert('success', data.message);
            form.reset();
            form.classList.remove('was-validated');
            document.getElementById('transaksiId').value = '';
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalTransaksi'));
            if (modal) modal.hide();
            
            loadTransaksi();
        } else {
            showAlert('danger', data.message || 'Gagal menyimpan transaksi');
        }
    })
    .catch(error => {
        button.disabled = false;
        button.innerHTML = originalText;
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan: ' + error.message);
    });
}

function editTransaksi(id) {
    const transaksi = allTransaksi.find(t => t.id == id);
    if (!transaksi) {
        showAlert('danger', 'Transaksi tidak ditemukan');
        return;
    }

    document.getElementById('transaksiId').value = transaksi.id;
    document.getElementById('jenisTransaksi').value = transaksi.jenis_transaksi;
    document.getElementById('tanggal').value = transaksi.tanggal;
    document.getElementById('kategori').value = transaksi.kategori;
    document.getElementById('deskripsi').value = transaksi.deskripsi || '';
    document.getElementById('nominal').value = formatCurrency(transaksi.nominal);
    document.getElementById('metode').value = transaksi.metode_pembayaran || '';
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil-square"></i> Edit Transaksi';

    const modal = new bootstrap.Modal(document.getElementById('modalTransaksi'));
    modal.show();
}

function deleteTransaksi(id) {
    const transaksi = allTransaksi.find(t => t.id == id);
    if (!transaksi) return;

    Swal.fire({
        title: 'Hapus Transaksi?',
        text: `Apakah Anda yakin ingin menghapus transaksi "${transaksi.kategori}" sebesar ${formatCurrency(transaksi.nominal)}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bi bi-trash"></i> Ya, Hapus',
        cancelButtonText: '<i class="bi bi-x-circle"></i> Batal',
        html: true
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);

            fetch('proses/hapus.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    loadTransaksi();
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Terjadi kesalahan saat menghapus');
            });
        }
    });
}

function viewDetail(id) {
    const transaksi = allTransaksi.find(t => t.id == id);
    if (!transaksi) return;

    const badgeClass = transaksi.jenis_transaksi === 'pemasukan' 
        ? 'badge-pemasukan' 
        : 'badge-pengeluaran';
    const tanggal = formatDate(transaksi.tanggal);

    let detail = `
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Tanggal:</strong> ${tanggal}</p>
                <p>
                    <strong>Jenis:</strong> 
                    <span class="badge ${badgeClass}">
                        ${transaksi.jenis_transaksi === 'pemasukan' 
                            ? '<i class="bi bi-arrow-up"></i> Pemasukan' 
                            : '<i class="bi bi-arrow-down"></i> Pengeluaran'}
                    </span>
                </p>
                <p><strong>Kategori:</strong> ${escapeHtml(transaksi.kategori)}</p>
            </div>
            <div class="col-md-6">
                <p>
                    <strong>Nominal:</strong> 
                    <span class="fw-bold text-${transaksi.jenis_transaksi === 'pemasukan' ? 'success' : 'danger'}">
                        ${formatCurrency(transaksi.nominal)}
                    </span>
                </p>
                <p><strong>Metode:</strong> ${escapeHtml(transaksi.metode_pembayaran || '-')}</p>
            </div>
        </div>
        <hr>
        <p><strong>Deskripsi:</strong></p>
        <p>${escapeHtml(transaksi.deskripsi || 'Tidak ada deskripsi')}</p>
    `;

    document.getElementById('detailContent').innerHTML = detail;
    document.getElementById('transaksiId').value = id;
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}

function editTransaksiFromDetail() {
    const id = document.getElementById('transaksiId').value;
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalDetail'));
    if (modal) modal.hide();
    editTransaksi(id);
}

function hapusTransaksiFromDetail() {
    const id = document.getElementById('transaksiId').value;
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalDetail'));
    if (modal) modal.hide();
    deleteTransaksi(id);
}

// ===== Search & Filter =====
function handleSearch() {
    const searchTerm = document.getElementById('searchTransaksi').value.toLowerCase();
    
    filteredTransaksi = allTransaksi.filter(item => {
        const kategori = (item.kategori || '').toLowerCase();
        const deskripsi = (item.deskripsi || '').toLowerCase();
        const metode = (item.metode_pembayaran || '').toLowerCase();
        
        return kategori.includes(searchTerm) || 
               deskripsi.includes(searchTerm) || 
               metode.includes(searchTerm);
    });
    
    currentPage = 1;
    displayTable();
}

function applyFilter() {
    const filterJenis = document.getElementById('filterJenis').value;
    const filterTanggalMulai = document.getElementById('filterTanggalMulai').value;
    const filterTanggalAkhir = document.getElementById('filterTanggalAkhir').value;

    filteredTransaksi = allTransaksi.filter(item => {
        if (filterJenis && item.jenis_transaksi !== filterJenis) return false;
        
        if (filterTanggalMulai && new Date(item.tanggal) < new Date(filterTanggalMulai)) return false;
        
        if (filterTanggalAkhir && new Date(item.tanggal) > new Date(filterTanggalAkhir)) return false;
        
        return true;
    });

    currentPage = 1;
    displayTable();
    showAlert('success', `Ditemukan ${filteredTransaksi.length} transaksi`);
}

function resetFilter() {
    document.getElementById('filterJenis').value = '';
    document.getElementById('filterTanggalMulai').value = '';
    document.getElementById('filterTanggalAkhir').value = '';
    document.getElementById('searchTransaksi').value = '';

    filteredTransaksi = [...allTransaksi];
    currentPage = 1;
    displayTable();
    showAlert('success', 'Filter direset');
}

// ===== Utility Functions =====
function formatCurrency(value) {
    if (!value) return 'Rp 0';
    const numValue = parseInt(String(value).replace(/[^0-9]/g, ''), 10);
    return 'Rp ' + numValue.toLocaleString('id-ID');
}

function formatCurrencyInput(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    if (value) {
        input.value = 'Rp ' + parseInt(value, 10).toLocaleString('id-ID');
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// ===== Alert Management =====
function showAlert(type, message) {
    const alertContainer = document.getElementById('alertContainer');
    if (!alertContainer) return;

    const alertId = 'alert-' + Date.now();
    const alert = document.createElement('div');
    alert.id = alertId;
    alert.className = `alert alert-${type} alert-dismissible fade show slide-in-up`;
    alert.setAttribute('role', 'alert');
    
    const icon = {
        'success': 'bi-check-circle-fill',
        'danger': 'bi-exclamation-circle-fill',
        'warning': 'bi-exclamation-triangle-fill',
        'info': 'bi-info-circle-fill'
    }[type] || 'bi-info-circle-fill';

    alert.innerHTML = `
        <i class="bi ${icon} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    alertContainer.appendChild(alert);

    setTimeout(() => {
        const element = document.getElementById(alertId);
        if (element) {
            element.classList.remove('show');
            setTimeout(() => element.remove(), 300);
        }
    }, 4000);
}

// ===== Export Functions =====
function exportPDF() {
    window.location.href = 'export_pdf.php';
}

function exportExcel() {
    window.location.href = 'export_excel.php';
}

function printLaporan() {
    window.print();
}
