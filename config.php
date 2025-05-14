<?php
$host = 'localhost';
$db   = 'internshala_quiz';
$user = 'your_db_username';
$pass = 'your_db_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>