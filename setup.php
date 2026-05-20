<?php
/**
 * CircleHub - Database Setup
 * Run this file once to create the database and tables
 * DELETE THIS FILE AFTER SETUP!
 */

$host = 'localhost';
$user = 'root';
$pass = 'root';

try {
    // Connect without database
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS circlehub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE circlehub");
    
    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_login TIMESTAMP NULL,
            INDEX idx_username (username),
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Setup Complete</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='alert alert-success'>
                <h4>Database Setup Successful!</h4>
                <p>The database and users table have been created.</p>
                <hr>
                <p class='mb-0'><strong>IMPORTANT:</strong> Delete this setup.php file now for security!</p>
            </div>
            <a href='auth/signup.php' class='btn btn-primary'>Go to Sign Up</a>
            <a href='auth/login.php' class='btn btn-outline-primary'>Go to Login</a>
        </div>
    </body>
    </html>";
    
} catch (PDOException $e) {
    echo "Setup failed: " . $e->getMessage();
}
?>
