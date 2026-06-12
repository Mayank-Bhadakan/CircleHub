<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$userId = $_SESSION['user_id'];
$friendId = (int)$_POST['friend_id'];
$message = trim($_POST['message']);

if ($message !== '') {

    $stmt = $pdo->prepare("
        INSERT INTO messages
        (sender_id, receiver_id, message)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([
        $userId,
        $friendId,
        $message
    ]);
}

header("Location: messages.php?friend_id=" . $friendId);
exit();