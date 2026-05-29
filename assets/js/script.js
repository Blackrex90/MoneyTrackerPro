// =====================================================
// MoneyTracker Pro - JavaScript Functions
// =====================================================

let allTransaksi = [];
let currentPage = 1;
let itemsPerPage = 10;
let filteredTransaksi = [];

// Initialize on document ready
document.addEventListener('DOMContentLoaded', function() {
    loadTransaksi();
    initializeCharts();
    setupEventListeners();
    loadDarkModePreference();
});

// Dark Mode Toggle
const darkModeToggle = document.getElementById('darkModeToggle');
if (darkModeToggle) {
    darkModeToggle.addEventListener('click', toggleDarkMode);
}

function toggleDarkMode() {
    const isDarkMode = document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
}

function loadDarkModePreference() {
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
    }
}

// Load Transaksi
function loadTransaksi() {
    fetch('proses/get_transaksi.php')
        .then(response => response.json())
        .then(data => {
            allTransaksi = data;
            filteredTransaksi = data;
            displayTable();
            updateStatistics();
            updateChart();
        })
        .catch(error => console.error('Error:', error));
}

// Display Table
function displayTable() {
    const tableContainer = document.getElementById('tableContainer');
    if (!tableContainer) return;

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginatedData = filteredTransaksi.slice(start, end);

    if (paginatedData.length === 0) {
        tableContainer.innerHTML = '<div class="alert alert-info">Tidak ada data transaksi</div>';
        document.getElementById('pagination').innerHTML = '';
        return;
    }

    let html = '<table class="table table-hover">';
    html += '<thead><tr>';
    html += '<th>Tanggal</th>';
    html += '<th>Jenis</th>';
    html += '<th>Kategori</th>';
    html += '<th>Deskripsi</th>';
    html += '<th class="text-end">Nominal</th>';
    html += '<th>Metode</th>';
    html += '<th class="text-center">Aksi</th>';
    html += '</tr></thead><tbody>';

    paginatedData.forEach(item => {
        const badgeClass = item.jenis_transaksi === 'pemasukan' ? 'badge-pemasukan' : 'badge-pengeluaran';
        const nominal = formatCurrency(item.nominal);
        const tanggal = new Date(item.tanggal).toLocaleDateString('id-ID');

        html += '<tr>';
        html += `<td>${tanggal}</td>`;
        html += `<td><span class="badge ${badgeClass}">${item.jenis_transaksi}</span></td>`;
        html += `<td>${item.kategori}</td>`;
        html += `<td>${item.deskripsi || '-'}</td>`;
        html += `<td class="text-end fw-bold ${item.jenis_transaksi === 'pemasukan' ? 'text-success' : 'text-danger'}">${nominal}</td>`;
        html += `<td>${item.metode_pembayaran || '-'}</td>`;
        html += '<td class="text-center">';
        html += `<button class="btn btn-sm btn-info me-1" onclick="viewDetail(${item.id})" title="Lihat"><i class="bi bi-eye"></i></button>`;
        html += `<button class="btn btn-sm btn-warning me-1" onclick="editTransaksi(${item.id})" title="Edit"><i class="bi bi-pencil"></i></button>`;
        html += `<button class="btn btn-sm btn-danger" onclick="deleteTransaksi(${item.id})" title="Hapus"><i class="bi bi-trash"></i></button>`;
        html += '</td>';
        html += '</tr>';
    });

    html += '</tbody></table>';
    tableContainer.innerHTML = html;
    displayPagination();
}

// Display Pagination
function displayPagination() {
    const pagination = document.getElementById('pagination');
    if (!pagination) return;

    const totalPages = Math.ceil(filteredTransaksi.length / itemsPerPage);
    pagination.innerHTML = '';

    if (totalPages <= 1) return;

    // Previous
    if (currentPage > 1) {
        pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${currentPage - 1})">Sebelumnya</a></li>`;
    }

    // Pages
    for (let i = 1; i <= totalPages; i++) {
        const active = i === currentPage ? 'active' : '';
        pagination.innerHTML += `<li class="page-item ${active}"><a class="page-link" href="#" onclick="goToPage(${i})">${i}</a></li>`;
    }

    // Next
    if (currentPage < totalPages) {
        pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${currentPage + 1})">Selanjutnya</a></li>`;
    }
}

function goToPage(page) {
    currentPage = page;
    displayTable();
    window.scrollTo(0, 0);
}

// Update Statistics
function updateStatistics() {
    const totalPemasukan = allTransaksi
        .filter(t => t.jenis_transaksi === 'pemasukan')
        .reduce((sum, t) => sum + parseFloat(t.nominal), 0);

    const totalPengeluaran = allTransaksi
        .filter(t => t.jenis_transaksi === 'pengeluaran')
        .reduce((sum, t) => sum + parseFloat(t.nominal), 0);

    const totalSaldo = totalPemasukan - totalPengeluaran;

    const totalSaldoEl = document.getElementById('totalSaldo');
    const totalPemasukanEl = document.getElementById('totalPemasukan');
    const totalPengeluaranEl = document.getElementById('totalPengeluaran');
    const totalTransaksiEl = document.getElementById('totalTransaksi');

    if (totalSaldoEl) totalSaldoEl.textContent = formatCurrency(totalSaldo);
    if (totalPemasukanEl) totalPemasukanEl.textContent = formatCurrency(totalPemasukan);
    if (totalPengeluaranEl) totalPengeluaranEl.textContent = formatCurrency(totalPengeluaran);
    if (totalTransaksiEl) totalTransaksiEl.textContent = allTransaksi.length;
}

// Initialize Charts
let chartInstance = null;

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
            monthlyData[monthKey].pemasukan += parseFloat(t.nominal);
        } else {
            monthlyData[monthKey].pengeluaran += parseFloat(t.nominal);
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
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaranData,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Save Transaksi
function saveTransaksi(event) {
    event.preventDefault();

    const id = document.getElementById('transaksiId').value;
    const jenis = document.getElementById('jenisTransaksi').value;
    const tanggal = document.getElementById('tanggal').value;
    const kategori = document.getElementById('kategori').value;
    const deskripsi = document.getElementById('deskripsi').value;
    const nominal = document.getElementById('nominal').value;
    const metode = document.getElementById('metode').value;

    if (!jenis || !tanggal || !kategori || !nominal) {
        showAlert('danger', 'Semua field required harus diisi!');
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

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            document.getElementById('formTransaksi').reset();
            document.getElementById('transaksiId').value = '';
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalTransaksi'));
            modal.hide();
            loadTransaksi();
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan!');
    });
}

// Edit Transaksi
function editTransaksi(id) {
    const transaksi = allTransaksi.find(t => t.id == id);
    if (!transaksi) return;

    document.getElementById('transaksiId').value = transaksi.id;
    document.getElementById('jenisTransaksi').value = transaksi.jenis_transaksi;
    document.getElementById('tanggal').value = transaksi.tanggal;
    document.getElementById('kategori').value = transaksi.kategori;
    document.getElementById('deskripsi').value = transaksi.deskripsi;
    document.getElementById('nominal').value = formatCurrency(transaksi.nominal);
    document.getElementById('metode').value = transaksi.metode_pembayaran;
    document.getElementById('modalTitle').textContent = 'Edit Transaksi';

    const modal = new bootstrap.Modal(document.getElementById('modalTransaksi'));
    modal.show();
}

// Delete Transaksi
function deleteTransaksi(id) {
    Swal.fire({
        title: 'Hapus Transaksi?',
        text: 'Data akan dihapus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
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
                showAlert('danger', 'Terjadi kesalahan!');
            });
        }
    });
}

// Reset Form
function resetForm(jenis = '') {
    document.getElementById('formTransaksi').reset();
    document.getElementById('transaksiId').value = '';
    document.getElementById('modalTitle').textContent = 'Tambah Transaksi';
    if (jenis) {
        document.getElementById('jenisTransaksi').value = jenis;
    }
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal').value = today;
}

// View Detail
function viewDetail(id) {
    const transaksi = allTransaksi.find(t => t.id == id);
    if (!transaksi) return;

    const badgeClass = transaksi.jenis_transaksi === 'pemasukan' ? 'badge-pemasukan' : 'badge-pengeluaran';
    const tanggal = new Date(transaksi.tanggal).toLocaleDateString('id-ID');

    let detail = `
        <div class="row">
            <div class="col-md-6">
                <p><strong>Tanggal:</strong> ${tanggal}</p>
                <p><strong>Jenis:</strong> <span class="badge ${badgeClass}">${transaksi.jenis_transaksi}</span></p>
                <p><strong>Kategori:</strong> ${transaksi.kategori}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Nominal:</strong> <span class="fw-bold text-${transaksi.jenis_transaksi === 'pemasukan' ? 'success' : 'danger'}">${formatCurrency(transaksi.nominal)}</span></p>
                <p><strong>Metode:</strong> ${transaksi.metode_pembayaran || '-'}</p>
            </div>
        </div>
        <hr>
        <p><strong>Deskripsi:</strong></p>
        <p>${transaksi.deskripsi || 'Tidak ada deskripsi'}</p>
    `;

    document.getElementById('detailContent').innerHTML = detail;
    document.getElementById('transaksiId').value = id;
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}

// Format Currency
function formatCurrency(value) {
    if (!value) return 'Rp 0';
    return 'Rp ' + parseInt(value).toLocaleString('id-ID');
}

function formatCurrencyInput(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    if (value) {
        input.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
    }
}

// Export Functions
function exportPDF() {
    window.location.href = 'export_pdf.php';
}

function exportExcel() {
    window.location.href = 'export_excel.php';
}

function printLaporan() {
    window.print();
}

// Alert Function
function showAlert(type, message) {
    const alertContainer = document.getElementById('alertContainer');
    if (!alertContainer) return;

    const alertId = 'alert-' + Date.now();
    const alert = document.createElement('div');
    alert.id = alertId;
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    alertContainer.appendChild(alert);

    setTimeout(() => {
        const element = document.getElementById(alertId);
        if (element) {
            element.remove();
        }
    }, 4000);
}

// Setup Event Listeners
function setupEventListeners() {
    const filterJenis = document.getElementById('filterJenis');
    if (filterJenis) {
        filterJenis.addEventListener('change', applyFilter);
    }
}
