<?php
/**
 * CircleHub - Profile Fields Setup
 * Run this file once to add profile fields to users table
 * DELETE THIS FILE AFTER SETUP!
 */

$host = 'localhost';
$user = 'root';
$pass = 'root';
$dbname = 'circlehub';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Add bio column if not exists
    $pdo->exec("
        ALTER TABLE users 
        ADD COLUMN bio TEXT DEFAULT NULL
    ");
    
    // Add profile_image column if not exists
    $pdo->exec("
        ALTER TABLE users 
        ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL
    ");
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Profile Setup Complete</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='alert alert-success'>
                <h4>Profile Fields Added Successfully!</h4>
                <p>The bio and profile_image columns have been added to the users table.</p>
                <hr>
                <p class='mb-0'><strong>IMPORTANT:</strong> Delete this setup_profile.php file now for security!</p>
            </div>
            <a href='index.php' class='btn btn-primary'>Go to Dashboard</a>
            <a href='profile/profile.php' class='btn btn-outline-primary'>Go to Profile</a>
        </div>
    </body>
    </html>";
    
} catch (PDOException $e) {
    echo "Setup failed: " . $e->getMessage();
}
?>
