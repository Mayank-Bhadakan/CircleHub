<?php
/**
 * CircleHub - Session Management
 * Handles session security and automatic expiry
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    // Session security settings
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Strict');
    
    session_start();
}

/**
 * Check Session Expiry
 * Automatically logs out user after SESSION_TIMEOUT (30 minutes)
 */
function checkSessionExpiry() {
    // Skip if not logged in
    if (!isset($_SESSION['user_id'])) {
        return;
    }
    
    $currentTime = time();
    
    // Check if last activity is set
    if (isset($_SESSION['last_activity'])) {
        $inactiveTime = $currentTime - $_SESSION['last_activity'];
        
        // If inactive for longer than SESSION_TIMEOUT
        if ($inactiveTime > SESSION_TIMEOUT) {
            // Store message before destroying session
            $expired = true;
            
            // Destroy the session
            session_unset();
            session_destroy();
            
            // Start new session for flash message
            session_start();
            $_SESSION['flash'] = [
                'type' => 'warning',
                'message' => 'Your session has expired due to inactivity. Please login again.'
            ];
            
            // Redirect to login
            header("Location: " . APP_URL . "/auth/login.php");
            exit();
        }
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = $currentTime;
}

/**
 * Initialize session for logged in user
 */
function initUserSession($user) {
    // Regenerate session ID for security
    session_regenerate_id(true);
    
    // Store user data in session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['logged_in_at'] = time();
    $_SESSION['last_activity'] = time();
}

/**
 * Destroy user session (logout)
 */
function destroyUserSession() {
    // Unset all session variables
    $_SESSION = array();
    
    // Delete session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
}

/**
 * Get remaining session time in minutes
 */
function getSessionTimeRemaining() {
    if (!isset($_SESSION['last_activity'])) {
        return 0;
    }
    
    $elapsed = time() - $_SESSION['last_activity'];
    $remaining = SESSION_TIMEOUT - $elapsed;
    
    return max(0, ceil($remaining / 60));
}

// Run session expiry check on every page load
checkSessionExpiry();
?>
