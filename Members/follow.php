<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if (!isset($_GET['id'])) {
    header("Location: discover.php");
    exit();
}

$userId = $_SESSION['user_id'];
$friendId = (int)$_GET['id'];

if ($userId == $friendId) {
    header("Location: discover.php");
    exit();
}

$check = $pdo->prepare("
    SELECT id
    FROM friends
    WHERE uid = ?
    AND friend_id = ?
");

$check->execute([$userId, $friendId]);

if ($check->rowCount() == 0) {

    $stmt = $pdo->prepare("
        INSERT INTO friends (uid, friend_id)
        VALUES (?, ?)
    ");

    $stmt->execute([$userId, $friendId]);
}

header("Location: discover.php");
exit();