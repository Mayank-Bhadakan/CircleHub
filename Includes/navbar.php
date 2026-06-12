<?php
/**
 * CircleHub - Navigation Bar
 */

// Get base URL for links
$baseUrl = '';
$currentScript = $_SERVER['SCRIPT_NAME'];
if (strpos($currentScript, '/auth/') !== false) {
    $baseUrl = '..';
} else if (strpos($currentScript, '/Members/') !== false) {
    $baseUrl = '..';
} else {
    $baseUrl = '.';
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo $baseUrl; ?>/index.php">
            <i class="bi bi-circle-fill me-2"></i><?php echo APP_NAME; ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isLoggedIn()): ?>
                    <!-- Logged In Menu -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>/index.php">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>/Members/friends.php">
                            <i class="bi bi-speedometer2 me-1"></i>Friends
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/CircleHub/Members/requests.php">
                            Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>/Members/discover.php">
                            <i class="bi bi-speedometer2 me-1"></i>Discover
                        </a>
                    </li>
                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li class="px-3 py-2">
                                <small class="text-muted">Logged in as</small>
                                <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="px-3 py-1">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>Session: <?php echo getSessionTimeRemaining(); ?> min left
                                </small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?php echo $baseUrl; ?>/auth/logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Guest Menu -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $baseUrl; ?>/auth/login.php">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-primary ms-2 px-3" href="<?php echo $baseUrl; ?>/auth/signup.php">
                            <i class="bi bi-person-plus me-1"></i>Sign Up
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

