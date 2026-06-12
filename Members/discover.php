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
                        $search = $_GET['search'] ?? '';

                        $query = $pdo->prepare("
                            SELECT id, full_name
                            FROM users
                            WHERE id != ?
                            AND id NOT IN (
                                SELECT friend_id
                                FROM friends
                                WHERE uid = ?
                            )
                            AND full_name LIKE ?
                        ");

                        $query->execute([
                            $uid,
                            $uid,
                            "%$search%"
                        ]);

                        $query_results = $query->fetchAll();
                        if (count($query_results) > 0) {
                            ?>
                            <p class="lead mb-0 opacity-75">
                                Want to find someone else to chat with? Try sending message to any of the following?
                            </p>
                            <br />
                           
                            <form method="GET" class="mb-4">

                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Search users..."
                                    value="<?php echo htmlspecialchars($search); ?>">

                                <button type="submit" class="btn btn-primary mt-2">
                                    Search
                                </button>

                                <a href="discover.php" class="btn btn-secondary mt-2"> Clear </a>

                            </form>

                            <div class="row">

                                <?php foreach ($query_results as $row): ?>

                                <div class="col-md-4 mb-4">

                                    <div class="card shadow-sm">

                                        <div class="card-body text-center">

                                            <img
                                                src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['full_name']); ?>"
                                                class="rounded-circle mb-3"
                                                width="100">

                                            <h5>
                                                <?php echo htmlspecialchars($row['full_name']); ?>
                                            </h5>

                                            <a href="follow.php?id=<?php echo $row['id']; ?>"
                                            class="btn btn-primary">
                                            Add Friend
                                            </a>

                                            <a href="../Profile/view_profile.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-secondary mt-2"> View Profile </a>

                                        </div>

                                    </div>

                                </div>

                                <?php endforeach; ?>

                             </div>


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