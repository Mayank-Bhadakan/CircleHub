<?php
/**
 * CircleHub - Helper Functions
 * Essential functions for signup, login, and session management
 */

/**
 * Sanitize user input
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email format
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate username (alphanumeric, 3-20 chars)
 */
function isValidUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

/**
 * Validate password strength (min 8 chars)
 */
function isValidPassword($password) {
    return strlen($password) >= 8;
}

/**
 * Check if username exists
 */
function usernameExists($pdo, $username) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch() !== false;
}

/**
 * Check if email exists
 */
function emailExists($pdo, $email) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch() !== false;
}

/**
 * Create new user
 */
function createUser($pdo, $username, $email, $password, $fullName) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("
        INSERT INTO users (username, email, password, full_name) 
        VALUES (?, ?, ?, ?)
    ");
    
    return $stmt->execute([$username, $email, $hashedPassword, $fullName]);
}

/**
 * Get user by username or email
 */
function getUserByLogin($pdo, $login) {
    $stmt = $pdo->prepare("
        SELECT * FROM users 
        WHERE username = ? OR email = ?
    ");
    $stmt->execute([$login, $login]);
    return $stmt->fetch();
}

/**
 * Update last login time
 */
function updateLastLogin($pdo, $userId) {
    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    return $stmt->execute([$userId]);
}

/**
 * Display flash message
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Redirect helper
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Require login - redirect to login page if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlashMessage('warning', 'Please login to access this page.');
        redirect(APP_URL . '/auth/login.php');
    }
}

/**
 * Require guest - redirect to dashboard if already logged in
 */
function requireGuest() {
    if (isLoggedIn()) {
        redirect(APP_URL . '/index.php');
    }
}

/**
 * Get user by ID
 */
function getUserById($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

/**
 * Update user profile
 */
function updateUserProfile($pdo, $userId, $fullName, $bio) {
    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, bio = ? WHERE id = ?");
    return $stmt->execute([$fullName, $bio, $userId]);
}

/**
 * Update profile image
 */
function updateProfileImage($pdo, $userId, $imagePath) {
    $stmt = $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
    return $stmt->execute([$imagePath, $userId]);
}

/**
 * Get profile image URL or default
 */
// function getProfileImage($user, $baseUrl = '.') {
//     if (!empty($user['profile_image']) && file_exists(__DIR__ . '/../uploads/profiles/' . $user['profile_image'])) {
//         return $baseUrl . '/uploads/profiles/' . $user['profile_image'];
//     }
//     return 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name']) . '&background=0d6efd&color=fff&size=150';
// }


function getProfileImage($user) {

    if (
        !empty($user['profile_image']) &&
        file_exists(__DIR__ . '/../uploads/profiles/' . $user['profile_image'])
    ) {
        return APP_URL . '/uploads/profiles/' . $user['profile_image'];
    }

    return 'https://ui-avatars.com/api/?name=' .
        urlencode($user['full_name']) .
        '&background=0d6efd&color=fff&size=150';
}


/**
 * Format date for display
 */
function formatDate($date) {
    if (empty($date)) return 'Never';
    return date('M j, Y g:i A', strtotime($date));
}
?>