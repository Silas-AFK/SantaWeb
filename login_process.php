<?php
session_start();

$matricule = $_POST['matricule'];
$password = $_POST['password'];

$conn = new mysqli('localhost', 'root', '', 'smcc_santa');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM admission WHERE matricule = ?");
$stmt->bind_param("s", $matricule);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Use plain password or hash as needed
    if ($password === $user['password'] || password_verify($password, $user['password'])) {
        $_SESSION['student'] = $user;
        header("Location: student_dashboard.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid password.";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['login_error'] = "Invalid matricule.";
    header("Location: login.php");
    exit();
}
