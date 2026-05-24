<?php
/**
 * CircleHub - Dashboard (Home Page)
 * Only accessible when logged in
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

// Require login to access dashboard
requireLogin();

// Get user data from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

// Get flash message
$flash = getFlashMessage();
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white border-0 shadow">
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-6 fw-bold mb-2">
                                Friends
                            </h1>
                            <p class="lead mb-0 opacity-75">
                                You can find your friend list here.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card text-bg-light border-0 shadow">
                <div class="card-body p-4 p-md-5">
                    <?php
                        $uid = $user['id'];
                        $query = $pdo->prepare("SELECT u.id FROM users u JOIN friends f ON u.id = f.friend_id WHERE f.uid = ?;");
                        $query->bindParam(1, $uid);
                        $query->execute();
                        $query_results = $query->fetchAll();
                        if (count($query_results) <= 0) {
                    ?>
                        <p class="lead mb-0 opacity-75">
                            You don't have any friends yet. Why not go to <a href="./discover.php">discover page</a> to find some?
                        </p>
                    <?php
                        }
                    ?>
                    <div class="accordion">
                        <?php
                        $uid = $user['id'];
                        $query = $pdo->prepare("SELECT u.id, full_name FROM users u JOIN friends f ON u.id = f.friend_id WHERE f.uid = ?;");
                        $query->bindParam(1, $uid);
                        $query->execute();
                        $query_results = $query->fetchAll();
                        foreach ($query_results as $row) {
                        ?>
                            <div class="accordion-item" id="friend-list">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="friend-list-item-<?= $row['id']; ?>" aria-expanded="true" aria-controls="friend-list-item-<?= $row['id']; ?>">
                                        <?= $row['full_name']; ?>
                                    </button>
                                </h2>
                                <div id="friend-list-item-<?= $row['id']; ?>" class="accordion-collapse collapse show" data-bs-parent="#friend-list">
                                    <div class="accordion-body">
                                        Click to send message
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>