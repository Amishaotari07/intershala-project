<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT id, username, password, role FROM users WHERE email = :email";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            if ($row = $stmt->fetch()) {
                if (password_verify($password, $row["password"])) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $row["id"];
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["role"] = $row["role"];

                    if ($row["role"] == 'admin') {
                        header("location: admin_dashboard.php");
                    } else {
                        header("location: dashboard.php");
                    }
                    exit();
                } else {
                    echo "Invalid password.";
                }
            }
        } else {
            echo "No account found with that email.";
        }
    }
}
?>
<form action="login.php" method="post">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Login</button>
</form>