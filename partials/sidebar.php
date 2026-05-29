<?php
// =====================================================
// MoneyTracker Pro - Sidebar Partial
// =====================================================
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h5><i class="bi bi-menu-button-wide"></i> Menu</h5>
    </div>
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="transaksi.php">
                    <i class="bi bi-list-check"></i> Daftar Transaksi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="laporan.php">
                    <i class="bi bi-file-earmark-bar-chart"></i> Laporan Keuangan
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-download"></i> Export
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="export_pdf.php"><i class="bi bi-filetype-pdf"></i> Export PDF</a></li>
                    <li><a class="dropdown-item" href="export_excel.php"><i class="bi bi-filetype-xlsx"></i> Export Excel</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>