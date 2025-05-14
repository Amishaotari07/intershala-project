<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit();
}
?>
<h1>Admin Dashboard</h1>
<p>Manage users and view statistics here.</p>
<a href="logout.php">Logout</a>