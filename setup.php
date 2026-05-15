<?php

require_once "config.php";

try {

    // Members Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS members (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Profiles Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS profiles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            bio TEXT,
            profile_image VARCHAR(255) DEFAULT 'default-profile.png',
            FOREIGN KEY (user_id) REFERENCES members(id)
            ON DELETE CASCADE
        )
    ");

    // Friends Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS friends (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            friend_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES members(id)
            ON DELETE CASCADE,
            FOREIGN KEY (friend_id) REFERENCES members(id)
            ON DELETE CASCADE
        )
    ");

    // Messages Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL,
            message TEXT NOT NULL,
            is_private TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sender_id) REFERENCES members(id)
            ON DELETE CASCADE,
            FOREIGN KEY (receiver_id) REFERENCES members(id)
            ON DELETE CASCADE
        )
    ");

    echo "All tables created successfully!";

} catch(PDOException $e) {

    die("Table Creation Failed: " . $e->getMessage());

}

?>