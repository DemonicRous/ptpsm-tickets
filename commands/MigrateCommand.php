#!/usr/bin/env php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Core\Database;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = Database::getInstance()->getConnection();
$sql = file_get_contents(__DIR__ . '/../migrations/001_create_tables.sql');

try {
    $db->exec($sql);
    echo "✅ Migration completed successfully.\n";
} catch (PDOException $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}