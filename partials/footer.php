<?php
// =====================================================
// MoneyTracker Pro - Footer Partial
// =====================================================
?>
    <!-- Footer -->
    <footer class="footer mt-5 py-4 bg-light border-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        <strong>MoneyTracker Pro</strong> &copy; <?php echo date('Y'); ?> - Aplikasi Pembukuan Keuangan
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> oleh Developer Indonesia
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo dirname($_SERVER['PHP_SELF']) == '/' ? '' : dirname($_SERVER['PHP_SELF']); ?>/assets/js/script.js"></script>
</body>
</html>