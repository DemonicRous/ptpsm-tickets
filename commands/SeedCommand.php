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

// === Добавляем отделы, если их нет ===
$stmt = $db->query("SELECT COUNT(*) FROM departments");
if ($stmt->fetchColumn() == 0) {
    $departments = [
        ['IT-отдел', 'Техническая поддержка'],
        ['Бухгалтерия', 'Финансовый отдел'],
        ['Администрация', 'Руководство'],
        ['Учебная часть', 'Организация учебного процесса']
    ];
    $sql = "INSERT INTO departments (name, description) VALUES (?, ?)";
    $stmt = $db->prepare($sql);
    foreach ($departments as $dept) {
        $stmt->execute([$dept[0], $dept[1]]);
    }
    echo "✅ Отделы добавлены\n";
}

// === Категории ===
$stmt = $db->query("SELECT COUNT(*) FROM categories");
if ($stmt->fetchColumn() == 0) {
    $categories = [
        ['Оборудование', null],
        ['Компьютеры', 1],
        ['Принтеры', 1],
        ['Программное обеспечение', null],
        ['ОС', 4],
        ['Сеть и интернет', null],
        ['Доступы и права', null],
    ];
    $sql = "INSERT INTO categories (name, parent_id) VALUES (?, ?)";
    $stmt = $db->prepare($sql);
    foreach ($categories as $cat) {
        $stmt->execute([$cat[0], $cat[1]]);
    }
    echo "✅ Категории добавлены\n";
}

// === Приоритеты ===
$stmt = $db->query("SELECT COUNT(*) FROM priorities");
if ($stmt->fetchColumn() == 0) {
    $priorities = [
        ['Низкий', 'green', 1],
        ['Средний', 'orange', 2],
        ['Высокий', 'red', 3],
        ['Критический', 'purple', 4]
    ];
    $sql = "INSERT INTO priorities (name, color, sort_order) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    foreach ($priorities as $pri) {
        $stmt->execute([$pri[0], $pri[1], $pri[2]]);
    }
    echo "✅ Приоритеты добавлены\n";
}