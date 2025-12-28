<?php
// Config: Database Connection
$host = 'localhost';
$db_name = 'bloodbank';
$username = 'root';
$password = ''; // Default XAMPP password

try {
    $dsn = "mysql:host=$host;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // In production, log this to a file instead of showing it
    die("Database connection failed: " . $e->getMessage());
}
?>
