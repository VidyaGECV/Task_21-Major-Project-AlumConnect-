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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $department = $_POST['department'] ?? '';
    $batch_year = $_POST['batch_year'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $linkedin_url = $_POST['linkedin_url'] ?? '';
    $profile_image = null;

    if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] == 0) {
        $target_dir = "uploads/";
        $file = $_FILES["profile_image"];
        $filename = basename($file["name"]);
        $new_filename = time() . "_" . $filename;
        $target_file = $target_dir . $new_filename;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                $profile_image = $target_file;
            }
        }
    }

    if ($profile_image) {
        $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, department=?, batch_year=?, bio=?, linkedin_url=?, profile_image=? WHERE id=?");
        $stmt->bind_param("sssssssi", $full_name, $email, $department, $batch_year, $bio, $linkedin_url, $profile_image, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, department=?, batch_year=?, bio=?, linkedin_url=? WHERE id=?");
        $stmt->bind_param("ssssssi", $full_name, $email, $department, $batch_year, $bio, $linkedin_url, $user_id);
    }
    $stmt->execute();
}

$stmt = $conn->prepare("SELECT full_name, email, department, batch_year, bio, linkedin_url, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f0f2f5;">

<div class="container mt-5">
    <div class="card mx-auto shadow" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="card-title mb-4 text-center text-primary">Edit Profile</h3>

            <div class="text-center mb-3">
                <?php if (!empty($user['profile_image'])): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" class="rounded-circle" style="width:120px; height:120px; object-fit:cover;">
                <?php else: ?>
                    <img src="default.jpg" class="rounded-circle" style="width:120px; height:120px; object-fit:cover;">
                <?php endif; ?>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required value="<?php echo htmlspecialchars($user['full_name']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">LinkedIn Profile URL</label>
                    <input type="url" name="linkedin_url" class="form-control" placeholder="https://linkedin.com/in/username" value="<?php echo htmlspecialchars($user['linkedin_url']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select name="department" class="form-select" required>
                        <option value="CSE" <?php if ($user['department'] == 'CSE') echo 'selected'; ?>>CSE</option>
                        <option value="ECE" <?php if ($user['department'] == 'ECE') echo 'selected'; ?>>ECE</option>
                        <option value="EEE" <?php if ($user['department'] == 'EEE') echo 'selected'; ?>>EEE</option>
                        <option value="ME" <?php if ($user['department'] == 'ME') echo 'selected'; ?>>ME</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Batch Year</label>
                    <select name="batch_year" class="form-select" required>
                        <?php
                        for ($y = date("Y"); $y >= 2000; $y--) {
                            $selected = ($user['batch_year'] == $y) ? 'selected' : '';
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Short Bio</label>
                    <textarea name="bio" rows="3" class="form-control"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Update Profile Image</label>
                    <input type="file" name="profile_image" class="form-control">
                </div>

                <button type="submit" class="btn btn-success w-100">Save Changes</button>
                <a href="index.php" class="btn btn-secondary mt-3 w-100">← Back to Home</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
