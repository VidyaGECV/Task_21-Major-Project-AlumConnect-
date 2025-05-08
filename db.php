<?php
$host = "sql313.infinityfree.com";
$user = "if0_38926488"; // replace with your DB username
$pass = "kcK0Fsmtcga";     // replace with your DB password
$db = "if0_38926488_alumni_portal";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
