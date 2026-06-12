<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if (!isset($_GET['friend_id'])) {
    die("Friend not selected.");
}

$userId = $_SESSION['user_id'];
$friendId = (int)$_GET['friend_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$friendId]);
$friend = $stmt->fetch();

$pageTitle = "Messages";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container py-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex align-items-center">

            <img
                src="<?php echo getProfileImage($friend); ?>"
                class="rounded-circle me-3"
                style="width:50px;height:50px;object-fit:cover;">

            <h5 class="mb-0">
                <?php echo htmlspecialchars($friend['full_name']); ?>
            </h5>

        </div>

        <div class="card-body" style="height:400px; overflow-y:auto;">

            <?php

            $stmt = $pdo->prepare("
                SELECT *
                FROM messages
                WHERE
                    (sender_id = ? AND receiver_id = ?)
                    OR
                    (sender_id = ? AND receiver_id = ?)
                ORDER BY created_at ASC
            ");

            $stmt->execute([
                $userId,
                $friendId,
                $friendId,
                $userId
            ]);

            $messages = $stmt->fetchAll();

            ?>

            <?php foreach ($messages as $message): ?>

                <?php if ($message['sender_id'] == $userId): ?>

                    <div class="text-end mb-2">

                        <div class="d-inline-block bg-primary text-white px-3 py-2 rounded">
                            <?php echo htmlspecialchars($message['message']); ?>
                        </div>

                    </div>

                <?php else: ?>

                    <div class="text-start mb-2">

                        <div class="d-inline-block bg-light border px-3 py-2 rounded">
                            <?php echo htmlspecialchars($message['message']); ?>
                        </div>

                    </div>

                <?php endif; ?>

            <?php endforeach; ?>


        </div>

        <div class="card-footer">

            <form action="send_message.php" method="POST">

                <input
                    type="hidden"
                    name="friend_id"
                    value="<?php echo $friendId; ?>">

                <div class="input-group">

                    <input
                        type="text"
                        name="message"
                        class="form-control"
                        placeholder="Type message..."
                        required>

                    <button
                        class="btn btn-primary"
                        type="submit">
                        Send
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
window.onload = function() {
    const chatBody = document.querySelector('.card-body');
    
    if(chatBody){
        chatBody.scrollTop = chatBody.scrollHeight;
    }
};
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>