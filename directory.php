<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
include('includes/db.php');

$search = $_GET['search'] ?? '';
$department = $_GET['department'] ?? '';
$batch_year = $_GET['batch_year'] ?? '';

$where = [];
$params = [];
$types = '';

if (!empty($search)) {
    $where[] = "(full_name LIKE ?)";
    $params[] = "%" . $search . "%";
    $types .= 's';
}
if (!empty($department)) {
    $where[] = "department = ?";
    $params[] = $department;
    $types .= 's';
}
if (!empty($batch_year)) {
    $where[] = "batch_year = ?";
    $params[] = $batch_year;
    $types .= 's';
}

$sql = "SELECT full_name, department, batch_year, bio, profile_image, email, linkedin_url FROM users";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY batch_year DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alumni Directory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f4f6f9;">

<div class="container mt-4">
    <h2 class="text-center text-primary mb-4">🎓 Alumni Directory</h2>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by name" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        </div>
        <div class="col-md-3">
            <select name="department" class="form-select">
                <option value="">All Departments</option>
                <option value="CSE(IOT)" <?php if (($_GET['department'] ?? '') == 'CSE(IOT)') echo 'selected'; ?>>CSE(IOT)</option>
                <option value="ECE" <?php if (($_GET['department'] ?? '') == 'ECE') echo 'selected'; ?>>ECE</option>
                <option value="EEE" <?php if (($_GET['department'] ?? '') == 'EEE') echo 'selected'; ?>>EEE</option>
                <option value="ME" <?php if (($_GET['department'] ?? '') == 'ME') echo 'selected'; ?>>ME</option>
                <option value="CE" <?php if (($_GET['department'] ?? '') == 'CE') echo 'selected'; ?>>CE</option>
                <option value="CSE" <?php if (($_GET['department'] ?? '') == 'CSE') echo 'selected'; ?>>CSE</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="batch_year" class="form-select">
                <option value="">All Batches</option>
                <?php
                for ($year = date("Y"); $year >= 2000; $year--) {
                    $selected = ($_GET['batch_year'] ?? '') == $year ? 'selected' : '';
                    echo "<option value=\"$year\" $selected>$year</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 text-center p-3">
                    <?php if (!empty($row['profile_image'])): ?>
                        <img src="<?php echo htmlspecialchars($row['profile_image']); ?>" class="img-fluid rounded-circle mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                    <?php else: ?>
                        <img src="default.jpg" class="img-fluid rounded-circle mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['full_name']); ?></h5>
                        <p class="mb-1"><strong>Department:</strong> <?php echo htmlspecialchars($row['department']); ?></p>
                        <p class="mb-1"><strong>Batch:</strong> <?php echo htmlspecialchars($row['batch_year']); ?></p>
                        <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($row['bio'])); ?></p>

                        <div class="mt-3">
                            <?php if (!empty($row['email'])): ?>
                                <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="btn btn-outline-primary btn-sm me-2">
                                    📧 Email
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($row['linkedin_url'])): ?>
                                <a href="<?php echo htmlspecialchars($row['linkedin_url']); ?>" target="_blank" class="btn btn-outline-info btn-sm">
                                    💼 LinkedIn
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">← Back to Home</a>
    </div>
</div>

</body>
</html>
