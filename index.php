<?php
/**
 * CircleHub - Dashboard (Home Page)
 * Only accessible when logged in
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';

// Require login to access dashboard
requireLogin();

// Get user data from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Get flash message
$flash = getFlashMessage();
?>

<div class="container py-5">
    <?php if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show">
            <?php echo $flash['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white border-0 shadow">
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-6 fw-bold mb-2">
                                Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!
                            </h1>
                            <p class="lead mb-0 opacity-75">
                                You are logged into your CircleHub dashboard.
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <i class="bi bi-person-circle" style="font-size: 5rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- User Information Card -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge text-primary me-2"></i>User Information
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted ps-0" style="width: 130px;">
                                <i class="bi bi-hash me-2"></i>User ID
                            </td>
                            <td class="fw-semibold"><?php echo $user['id']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">
                                <i class="bi bi-person me-2"></i>Full Name
                            </td>
                            <td class="fw-semibold"><?php echo htmlspecialchars($user['full_name']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">
                                <i class="bi bi-at me-2"></i>Username
                            </td>
                            <td class="fw-semibold"><?php echo htmlspecialchars($user['username']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">
                                <i class="bi bi-envelope me-2"></i>Email
                            </td>
                            <td class="fw-semibold"><?php echo htmlspecialchars($user['email']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">
                                <i class="bi bi-calendar me-2"></i>Joined
                            </td>
                            <td class="fw-semibold"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Session Status Card -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-check text-success me-2"></i>Session Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3 p-3 bg-success bg-opacity-10 rounded">
                        <i class="bi bi-check-circle-fill text-success fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold">Session Active</div>
                            <small class="text-muted">Your session is currently valid</small>
                        </div>
                    </div>
                    
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted ps-0" style="width: 150px;">
                                <i class="bi bi-clock me-2"></i>Time Remaining
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <?php echo getSessionTimeRemaining(); ?> minutes
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">
                                <i class="bi bi-hourglass me-2"></i>Timeout Setting
                            </td>
                            <td class="fw-semibold"><?php echo SESSION_TIMEOUT / 60; ?> minutes</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">
                                <i class="bi bi-clock-history me-2"></i>Logged in at
                            </td>
                            <td class="fw-semibold">
                                <?php echo date('h:i:s A', $_SESSION['logged_in_at']); ?>
                            </td>
                        </tr>
                    </table>
                    
                    <div class="alert alert-info mt-3 mb-0 small">
                        <i class="bi bi-info-circle me-2"></i>
                        Your session will expire after <?php echo SESSION_TIMEOUT / 60; ?> minutes of inactivity.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Logout Button -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Ready to leave?</h6>
                            <small class="text-muted">Click logout to end your session securely.</small>
                        </div>
                        <a href="./auth/logout.php" class="btn btn-danger px-4">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
