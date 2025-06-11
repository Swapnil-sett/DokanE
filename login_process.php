<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        header("Location: login.php?error=Username or password cannot be empty.");
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        
        if ($password === $user['password']) { 
            $_SESSION['username'] = $user['username'];

            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: login.php?error=Invalid password.");
            exit;
        }
    } else {
        header("Location: login.php?error=User not found.");
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php?error=Invalid request.");
    exit;
}
?>