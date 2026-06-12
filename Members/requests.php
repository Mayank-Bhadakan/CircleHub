<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT fr.id,
           u.full_name,
           u.id AS sender_id
    FROM friend_requests fr
    JOIN users u ON fr.sender_id = u.id
    WHERE fr.receiver_id = ?
    AND fr.status = 'pending'
");

$stmt->execute([$userId]);

$requests = $stmt->fetchAll();

$pageTitle = 'Friend Requests';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container py-5">

    <h2 class="mb-4">Friend Requests</h2>

    <?php if(count($requests) > 0): ?>

        <?php foreach($requests as $request): ?>

            <div class="card mb-3">
                <div class="card-body">

                    <h5>
                        <?php echo htmlspecialchars($request['full_name']); ?>
                    </h5>

                    <a
                        href="accept_request.php?id=<?php echo $request['id']; ?>"
                        class="btn btn-success">
                        Accept
                    </a>

                    <a
                        href="reject_request.php?id=<?php echo $request['id']; ?>"
                        class="btn btn-danger">
                        Reject
                    </a>

                </div>
            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="alert alert-info">
            No pending friend requests.
        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>