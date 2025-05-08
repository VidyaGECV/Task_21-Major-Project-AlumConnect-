<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// If you're using session variables
session_start();

// Debug output
echo "<!-- Debug: PHP is running -->";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome - Alumni Network</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity>
    <style>
        body {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            font-family: 'Poppins', sans-serif;
        }
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
        }
        .btn-custom {
            padding: 0.75rem 2rem;
            font-size: 1.2rem;
            border-radius: 2rem;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1 class="display-4 mb-3">
            <i class="fas fa-graduation-cap me-2"></i> Alum Connect
        </h1>
        <h2 class="mb-4">Get Connected to your Alumni....</h2>
        <p class="lead mb-5">Stay connected with your college family. Explore memories. Reconnect. Grow.</p>
        
        <div class="d-flex gap-3 mb-4">
            <a href="register.php" class="btn btn-light btn-custom">Register</a>
            <a href="login.php" class="btn btn-outline-light btn-custom">Login</a>
        </div>
        
        <div class="d-flex flex-column gap-3">
            <a href="directory.php" class="btn btn-primary btn-custom">
                <i class="fas fa-users me-2"></i> View Alumni Directory
            </a>
            <a href="memories.php" class="btn btn-warning btn-custom">
                <i class="fas fa-images me-2"></i> Visit Memories Wall
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>