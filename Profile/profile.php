<?php
/**
 * CircleHub - User Profile Page
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlashMessage('warning', 'Please login to view your profile.');
    redirect('../auth/login.php');
}

// Get current user data
$user = getUserById($pdo, $_SESSION['user_id']);

if (!$user) {
    setFlashMessage('error', 'User not found.');
    redirect('../auth/logout.php');
}

$baseUrl = '..';
$basePath = '..';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $basePath; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    
    <div class="container py-5">
        <?php
        $flash = getFlashMessage();
        if ($flash):
        ?>
        <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($flash['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Profile Card -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <!-- <img src="<?php echo getProfileImage($user, $baseUrl); ?>" 
                             alt="Profile" 
                             class="rounded-circle mb-3 border border-4 border-primary"
                             style="width: 150px; height: 150px; object-fit: cover;"> -->


                        <img src="<?php echo getProfileImage($user); ?>"
                            alt="Profile"
                            class="rounded-circle mb-3 border border-4 border-primary"
                            style="width: 150px; height: 150px; object-fit: cover;">

                        
                        <h4 class="mb-1"><?php echo htmlspecialchars($user['full_name']); ?></h4>
                        <p class="text-muted mb-3">@<?php echo htmlspecialchars($user['username']); ?></p>
                        
                        <a href="edit_profile.php" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Profile Details -->
            <div class="col-lg-8">
                <!-- Bio Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i>About Me</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($user['bio'])): ?>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
                        <?php else: ?>
                            <p class="text-muted mb-0 fst-italic">No bio added yet. <a href="edit_profile.php">Add one now</a></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Account Info Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Account Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Username</label>
                                <p class="mb-0 fw-semibold">@<?php echo htmlspecialchars($user['username']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Email Address</label>
                                <p class="mb-0 fw-semibold"><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Member Since</label>
                                <p class="mb-0 fw-semibold"><?php echo formatDate($user['created_at']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Last Login</label>
                                <p class="mb-0 fw-semibold"><?php echo formatDate($user['last_login']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Session Info Card -->
                <div class="card shadow-sm border-info">
                    <div class="card-header bg-info bg-opacity-10">
                        <h5 class="mb-0 text-info"><i class="bi bi-shield-check me-2"></i>Current Session</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-1"><strong>Session Time Remaining:</strong> <?php echo getSessionTimeRemaining(); ?> minutes</p>
                                <p class="mb-0 text-muted small">Session will expire after <?php echo SESSION_TIMEOUT / 60; ?> minutes of inactivity.</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="../auth/logout.php" class="btn btn-outline-danger">
                                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
