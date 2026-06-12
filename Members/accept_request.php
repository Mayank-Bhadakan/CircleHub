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
    SELECT *
    FROM friend_requests
    WHERE id = ?
");

$stmt->execute([$requestId]);

$request = $stmt->fetch();

if (!$request) {
    header("Location: requests.php");
    exit();
}

/*
    Add friendship both directions
*/

$stmt = $pdo->prepare("
    INSERT INTO friends (uid, friend_id)
    VALUES (?, ?)
");

$stmt->execute([
    $request['sender_id'],
    $request['receiver_id']
]);

$stmt->execute([
    $request['receiver_id'],
    $request['sender_id']
]);

/*
    Mark request accepted
*/

$stmt = $pdo->prepare("
    UPDATE friend_requests
    SET status = 'accepted'
    WHERE id = ?
");

$stmt->execute([$requestId]);

header("Location: requests.php");
exit();