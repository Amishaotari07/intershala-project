<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        if ($stmt->execute()) {
            header("location: login.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
}
?>
<form action="register.php" method="post">
    <input type="text" name="username" required placeholder="Username">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Register</button>
</form>