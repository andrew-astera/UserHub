<?php
// ============================
// Database configuration
// ============================
$DB_HOST = "localhost";
$DB_NAME = "mysite_db";
$DB_USER = "root";
$DB_PASS = "";        // put your MySQL password here
$DB_CHARSET = "utf8mb4";

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$DB_CHARSET";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Attempt to connect to the database
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    // Stop execution and show the connection error
    die("Database connection error: " . $e->getMessage());
}
