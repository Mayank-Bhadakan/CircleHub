<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if (!isset($_GET['id'])) {
    header("Location: requests.php");
    exit();
}

$requestId = (int)$_GET['id'];

$stmt = $pdo->prepare("
    UPDATE friend_requests
    SET status = 'rejected'
    WHERE id = ?
");

$stmt->execute([$requestId]);

header("Location: requests.php");
exit();