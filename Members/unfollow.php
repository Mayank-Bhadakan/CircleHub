<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if (!isset($_GET['id'])) {
    header("Location: friends.php");
    exit();
}

$userId = $_SESSION['user_id'];
$friendId = (int)$_GET['id'];

$stmt = $pdo->prepare("
    DELETE FROM friends
    WHERE uid = ?
    AND friend_id = ?
");

$stmt->execute([$userId, $friendId]);

header("Location: friends.php");
exit();