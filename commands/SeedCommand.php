#!/usr/bin/env php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Core\Database;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = Database::getInstance()->getConnection();

// Проверяем, есть ли администратор
$stmt = $db->prepare("SELECT COUNT(*) FROM `user` WHERE `role` = 'Администратор'");
$stmt->execute();
$adminCount = $stmt->fetchColumn();

if ($adminCount == 0) {
    $hashed = password_hash('admin123', PASSWORD_BCRYPT);
    $sql = "INSERT INTO `user` (surname, name, patronymic, login, email, phone, password, role)
            VALUES ('Административный', 'Админ', 'Админович', 'copp', 'admin@ptpsm.ru', '+7(999)-999-99-99', :pass, 'Администратор')";
    $stmt = $db->prepare($sql);
    $stmt->execute([':pass' => $hashed]);
    echo "✅ Администратор создан (логин: copp, пароль: admin123)\n";
} else {
    echo "ℹ️ Администратор уже существует\n";
}

// Создаём тестового пользователя, если нет
$stmt = $db->prepare("SELECT COUNT(*) FROM `user` WHERE `login` = 'ivan'");
$stmt->execute();
if ($stmt->fetchColumn() == 0) {
    $hashedUser = password_hash('ivan123', PASSWORD_BCRYPT);
    $sql = "INSERT INTO `user` (surname, name, patronymic, login, email, phone, password, role)
            VALUES ('Иванов', 'Иван', 'Иванович', 'ivan', 'ivan@mail.ru', '+7(922)-234-21-22', :pass, 'Пользователь')";
    $stmt = $db->prepare($sql);
    $stmt->execute([':pass' => $hashedUser]);
    echo "✅ Пользователь ivan создан (пароль: ivan123)\n";
}

// Добавляем тестовую заявку, если нет
$stmt = $db->prepare("SELECT COUNT(*) FROM `application`");
$stmt->execute();
if ($stmt->fetchColumn() == 0) {
    $userId = $db->query("SELECT user_id FROM user WHERE login='ivan'")->fetchColumn();
    $appNumber = "APP-" . date('Ymd') . "-001";
    $sql = "INSERT INTO `application` (user_id, number, status, name_org, message) 
            VALUES (:user_id, :number, 'Новый', 'МБОУ СОШ №4', 'Не работает принтер')";
    $stmt = $db->prepare($sql);
    $stmt->execute([':user_id' => $userId, ':number' => $appNumber]);
    echo "✅ Тестовая заявка создана\n";
}