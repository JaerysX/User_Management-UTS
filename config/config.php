<?php
// config/config.php

$host = 'localhost';
$dbname = 'user_management'; // nama database sama dengan nama folder
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Database connected successfully"; // aktifkan untuk testing
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}