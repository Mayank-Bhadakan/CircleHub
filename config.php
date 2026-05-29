<?php
/**
 * CircleHub - Configuration File
 * Database connection and app settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');  // MAMP default password
define('DB_NAME', 'circlehub');

// App Configuration
define('APP_NAME', 'CircleHub');
define('APP_URL', 'http://localhost:8888/CircleHub');

// Auto-detect APP_URL based on current location
// $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
// $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8888';
// $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
// // Get the base path (go up if we're in a subdirectory like /auth)
// $basePath = $scriptPath;
// if (strpos($scriptPath, '/auth') !== false) {
//     $basePath = dirname($scriptPath);
// } elseif (strpos($scriptPath, '/includes') !== false) {
//     $basePath = dirname($scriptPath);
// }
// define('APP_URL', $protocol . '://' . $host . $basePath);

// Session Configuration
date_default_timezone_set('America/Los_Angeles');
define('SESSION_TIMEOUT', 1800); // 30 minutes in seconds

// Create database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
