<?php
// src/config/database.php

require_once 'config.php';  // Include configuration constants

// Establish PDO connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set character set to UTF-8
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Optionally, you can define functions or classes for database operations using $pdo

?>
