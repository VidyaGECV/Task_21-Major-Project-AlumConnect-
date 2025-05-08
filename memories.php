<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Post Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['content'])) {
    $user_id = $_SESSION['user_id'];
    $content = htmlspecialchars(trim($_POST['content']));
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $content);
    $stmt->execute();
}

// Fetch posts
$query = "SELECT p.content, p.created_at, u.full_name FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Memories Wall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f4f6f9;">

<div class="container mt-4">
    <h2 class="text-center text-primary mb-4">📝 Alumni Memories Wall</h2>

    <!-- Post Form -->
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <textarea name="content" class="form-control" rows="3" placeholder="Share a memory or achievement..." required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Post Memory</button>
    </form>

    <!-- Post List -->
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-primary"><?php echo htmlspecialchars($row['full_name']); ?></h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <small class="text-muted"><?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></small>
            </div>
        </div>
    <?php endwhile; ?>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">← Back to Home</a>
    </div>
</div>

</body>
</html>
