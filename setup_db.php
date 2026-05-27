<?php
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'ptpsm_tickets';

try {
    // При создании БД charset указывается в SQL, а не в DSN
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "База данных '$dbname' готова.\n";
} catch (PDOException $e) {
    echo "Ошибка создания БД: " . $e->getMessage() . "\n";
    exit(1);
}