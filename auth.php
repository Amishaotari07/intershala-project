<?php
session_start();
require 'db.php'; // Database connection

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Redirect if user is not logged in and trying to access travel details
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) !== 'login.php' && basename($_SERVER['PHP_SELF']) !== 'register.php') {
    header("Location: login.php");
    exit();
}

// Registration Logic with 50% off for new users
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    $stmt = $conn->prepare("INSERT INTO users (email, password, discount) VALUES (?, ?, 50)");
    $stmt->bind_param("ss", $email, $password);
    
    if ($stmt->execute()) {
        echo "Registration successful. You get a 50% discount on your first booking! Please login.";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Login Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    $stmt = $conn->prepare("SELECT id, email, password, discount FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['discount'] = $user['discount'];
            $_SESSION['logged_in'] = true;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid credentials";
        }
    } else {
        echo "Invalid credentials";
    }
    
    $stmt->close();
}

// Logout Logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Payment Cancellation Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_payment'])) {
    $booking_id = $_POST['booking_id'];
    
    $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        echo "Your payment and booking have been cancelled.";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
?>
