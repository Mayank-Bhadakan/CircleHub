<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if (!isset($_GET['id'])) {
    die("User not found.");
}

$userId = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM users
    WHERE id = ?
");

$stmt->execute([$userId]);

$profile = $stmt->fetch();

if (!$profile) {
    die("User not found.");
}

$pageTitle = "User Profile";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

?>

<div class="container py-5">

    <div class="card shadow">

        <div class="card-body text-center">

            <?php
                $imagePath = !empty($profile['profile_image'])
                    ? "../uploads/profiles/" . $profile['profile_image']
                    : "https://ui-avatars.com/api/?name=" . urlencode($profile['full_name']);
                ?>

                <img
                    src="<?php echo $imagePath; ?>"
                    class="rounded-circle mb-3 border border-3 border-primary"
                    width="150"
                    height="150"
                    style="object-fit: cover;">

            <h2>
                <?php echo htmlspecialchars($profile['full_name']); ?>
            </h2>

            <hr>

            <p>
                <strong>Username:</strong>
                <?php echo htmlspecialchars($profile['username']); ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?php echo htmlspecialchars($profile['email']); ?>
            </p>

            <p>
                <strong>Joined:</strong>
                <?php echo htmlspecialchars($profile['created_at']); ?>
            </p>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>