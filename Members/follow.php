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

/*
|--------------------------------------------------------------------------
| Check if already friends
|--------------------------------------------------------------------------
*/
$checkFriend = $pdo->prepare("
    SELECT id
    FROM friends
    WHERE uid = ?
    AND friend_id = ?
");

$checkFriend->execute([$userId, $friendId]);

/*
|--------------------------------------------------------------------------
| Check if request already exists
|--------------------------------------------------------------------------
*/
$checkRequest = $pdo->prepare("
    SELECT id
    FROM friend_requests
    WHERE sender_id = ?
    AND receiver_id = ?
    AND status = 'pending'
");

$checkRequest->execute([$userId, $friendId]);

/*
|--------------------------------------------------------------------------
| Insert request only if not friend and no pending request
|--------------------------------------------------------------------------
*/
if (
    $checkFriend->rowCount() == 0 &&
    $checkRequest->rowCount() == 0
) {

    $stmt = $pdo->prepare("
        INSERT INTO friend_requests
        (sender_id, receiver_id)
        VALUES (?, ?)
    ");

    $stmt->execute([
        $userId,
        $friendId
    ]);
}

header("Location: discover.php");
exit();