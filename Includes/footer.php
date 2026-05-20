<?php
/**
 * CircleHub - Footer Include
 */

// Determine base path for assets
$basePath = '';
$currentScript = $_SERVER['SCRIPT_NAME'];
if (strpos($currentScript, '/auth/') !== false) {
    $basePath = '..';
} else {
    $basePath = '.';
}
?>

<footer class="bg-white border-top mt-5 py-4">
    <div class="container">
        <div class="text-center text-muted">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script src="<?php echo $basePath; ?>/assets/js/script.js"></script>

<!-- Auto-dismiss alerts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>

</body>
</html>
