<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit();
}
?>
<h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
<p>This is your dashboard.</p>
<a href="logout.php">Logout</a>