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

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT full_name, email, department, batch_year, bio, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Alumni Network</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header {
            background-color: #007bff;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
        }

        .btn-wide {
            min-width: 180px;
        }
    </style>
</head>
<body style="background-color: #f0f2f5;">

<div class="header">
    <h4>🎓 Alumni Dashboard</h4>
    <a href="logout.php" class="btn btn-outline-light">Logout</a>
</div>

<div class="container mt-5 text-center">
    <!-- Profile Image -->
    <div class="mb-4">
        <?php if (!empty($user['profile_image'])): ?>
            <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" class="profile-image" alt="Profile Photo">
        <?php else: ?>
            <img src="default.jpg" class="profile-image" alt="Default Photo">
        <?php endif; ?>
    </div>

    <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-body text-start">
            <h4 class="card-title">Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h4>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($user['department']); ?></p>
            <p><strong>Batch Year:</strong> <?php echo htmlspecialchars($user['batch_year']); ?></p>
            <p><strong>Bio:</strong> <?php echo htmlspecialchars($user['bio']); ?></p>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center gap-3">
        <a href="profile.php" class="btn btn-info text-white btn-wide">✏️ Edit Profile</a>
        <a href="directory.php" class="btn btn-secondary btn-wide">👥 View Alumni</a>
    </div>
</div>

</body>
</html>
