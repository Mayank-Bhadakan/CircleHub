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
                                Discover
                            </h1>
                            <p class="lead mb-0 opacity-75">
                                This is a discover page. It discovers new friends for you.
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
                    <div class="row align-items-center">
                        <?php
                        $uid = $user['id'];
                        $query = $pdo->prepare("SELECT u.id, u.full_name FROM users u WHERE NOT EXISTS (SELECT 1 FROM friends f WHERE f.uid = u.id AND f.friend_id = ?) AND u.id != ?;");
                        $query->bindParam(1, $uid);
                        $query->bindParam(2, $uid);
                        $query->execute();
                        $query_results = $query->fetchAll();
                        if (count($query_results) > 0) {
                            ?>
                            <p class="lead mb-0 opacity-75">
                                Want to find someone else to chat with? Try sending message to any of the following?
                            </p>
                            <br />
                            <ul>
                                <?php
                                foreach ($query_results as $row) {
                                    echo "<li>User: " . $row['full_name'] . "</li>";
                                }
                                ?>
                            </ul>
                        <?php
                        } else {
                        ?>
                            <p class="lead mb-0 opacity-75">
                                Hey! You managed to be friend with every other user here!
                            </p>
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