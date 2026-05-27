#!/usr/bin/env php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Core\Database;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = Database::getInstance()->getConnection();

// Флаг --fresh для полной очистки
if (in_array('--fresh', $argv)) {
    echo "🔥 Очистка таблиц...\n";
    $db->exec("SET FOREIGN_KEY_CHECKS=0");
    $tables = ['application_history', 'application_comments', 'attachments', 'notifications', 'application', 'user', 'departments', 'categories', 'priorities'];
    foreach ($tables as $table) {
        $db->exec("TRUNCATE TABLE `$table`");
    }
    $db->exec("SET FOREIGN_KEY_CHECKS=1");
    echo "✅ Таблицы очищены.\n";
}

function randomElement($array) {
    return $array[array_rand($array)];
}

function randomDate($start, $end) {
    $timestamp = mt_rand(strtotime($start), strtotime($end));
    return date('Y-m-d H:i:s', $timestamp);
}

echo "🌱 Заполнение базы данных тестовыми данными...\n";

// =====================================================
// 1. Отделы
// =====================================================
$departments = [
    ['IT-отдел', 'Техническая поддержка и администрирование'],
    ['Бухгалтерия', 'Финансовый отдел'],
    ['Отдел кадров', 'Кадровое делопроизводство'],
    ['Администрация', 'Руководство'],
    ['Учебная часть', 'Организация учебного процесса'],
    ['Хозяйственный отдел', 'Материально-техническое обеспечение'],
    ['Отдел продаж', 'Работа с клиентами'],
];
$stmt = $db->prepare("INSERT IGNORE INTO departments (name, description) VALUES (?, ?)");
foreach ($departments as $dept) {
    $stmt->execute($dept);
}
echo "✅ Отделы добавлены/обновлены\n";

// =====================================================
// 2. Приоритеты
// =====================================================
$prioritiesData = [
    ['Низкий', 'green', 1],
    ['Средний', 'orange', 2],
    ['Высокий', 'red', 3],
    ['Критический', 'purple', 4],
];
$stmt = $db->prepare("INSERT IGNORE INTO priorities (name, color, sort_order) VALUES (?, ?, ?)");
foreach ($prioritiesData as $pri) {
    $stmt->execute($pri);
}
echo "✅ Приоритеты добавлены\n";

// =====================================================
// 3. Категории
// =====================================================
$categoriesData = [
    ['Оборудование', null],
    ['Компьютеры', 1],
    ['Принтеры', 1],
    ['Программное обеспечение', null],
    ['ОС', 4],
    ['Сеть и интернет', null],
    ['Доступы и права', null],
];
$stmt = $db->prepare("INSERT IGNORE INTO categories (name, parent_id) VALUES (?, ?)");
foreach ($categoriesData as $cat) {
    $stmt->execute($cat);
}
echo "✅ Категории добавлены\n";

// =====================================================
// 4. Пользователи
// =====================================================
$depMap = [];
$depts = $db->query("SELECT department_id, name FROM departments")->fetchAll(PDO::FETCH_ASSOC);
foreach ($depts as $d) {
    $depMap[$d['name']] = $d['department_id'];
}

// Удалим старого пользователя copp, если он существует с неправильным паролем
$db->exec("DELETE FROM user WHERE login = 'copp'");

$usersData = [
    // Администратор (пароль admin123)
    ['surname' => 'Административный', 'name' => 'Админ', 'patronymic' => 'Админович', 'login' => 'copp', 'email' => 'admin@ptpsm.ru', 'phone' => '+7(999)-999-99-99', 'role' => 'Администратор', 'department' => 'Администрация', 'position' => 'Системный администратор', 'password' => password_hash('admin123', PASSWORD_BCRYPT)],
    // Обычные пользователи (пароль password123)
    ['surname' => 'Петров', 'name' => 'Иван', 'patronymic' => 'Сергеевич', 'login' => 'petrov', 'email' => 'petrov@ptpsm.ru', 'phone' => '+7(912)-345-67-89', 'role' => 'Пользователь', 'department' => 'Администрация', 'position' => 'Директор', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
    ['surname' => 'Сидорова', 'name' => 'Ольга', 'patronymic' => 'Николаевна', 'login' => 'sidorova', 'email' => 'sidorova@ptpsm.ru', 'phone' => '+7(922)-111-22-33', 'role' => 'Пользователь', 'department' => 'Учебная часть', 'position' => 'Завуч', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
    ['surname' => 'Иванов', 'name' => 'Петр', 'patronymic' => 'Алексеевич', 'login' => 'ivanov_p', 'email' => 'p.ivanov@ptpsm.ru', 'phone' => '+7(902)-123-45-67', 'role' => 'Пользователь', 'department' => 'IT-отдел', 'position' => 'Инженер', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
    ['surname' => 'Кузнецов', 'name' => 'Андрей', 'patronymic' => 'Викторович', 'login' => 'kuznetsov', 'email' => 'kuznetsov@ptpsm.ru', 'phone' => '+7(903)-987-65-43', 'role' => 'Пользователь', 'department' => 'IT-отдел', 'position' => 'Программист', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
    ['surname' => 'Смирнов', 'name' => 'Дмитрий', 'patronymic' => 'Андреевич', 'login' => 'smirnov', 'email' => 'smirnov@ptpsm.ru', 'phone' => '+7(904)-555-66-77', 'role' => 'Пользователь', 'department' => 'IT-отдел', 'position' => 'Сисадмин', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
    ['surname' => 'Зайцева', 'name' => 'Елена', 'patronymic' => 'Александровна', 'login' => 'zayceva', 'email' => 'zayceva@ptpsm.ru', 'phone' => '+7(905)-444-33-22', 'role' => 'Пользователь', 'department' => 'Бухгалтерия', 'position' => 'Главный бухгалтер', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
    ['surname' => 'Морозова', 'name' => 'Татьяна', 'patronymic' => 'Ивановна', 'login' => 'morozova', 'email' => 'morozova@ptpsm.ru', 'phone' => '+7(906)-777-88-99', 'role' => 'Пользователь', 'department' => 'Бухгалтерия', 'position' => 'Экономист', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
    ['surname' => 'Волкова', 'name' => 'Наталья', 'patronymic' => 'Сергеевна', 'login' => 'volkova', 'email' => 'volkova@ptpsm.ru', 'phone' => '+7(907)-123-45-67', 'role' => 'Пользователь', 'department' => 'Отдел кадров', 'position' => 'Специалист по кадрам', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
    ['surname' => 'Соколова', 'name' => 'Анна', 'patronymic' => 'Петровна', 'login' => 'sokolova', 'email' => 'sokolova@ptpsm.ru', 'phone' => '+7(908)-321-54-76', 'role' => 'Пользователь', 'department' => 'Учебная часть', 'position' => 'Методист', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
    ['surname' => 'Михайлов', 'name' => 'Сергей', 'patronymic' => 'Васильевич', 'login' => 'mikhailov', 'email' => 'mikhailov@ptpsm.ru', 'phone' => '+7(909)-876-54-32', 'role' => 'Пользователь', 'department' => 'Учебная часть', 'position' => 'Преподаватель', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
    ['surname' => 'Новиков', 'name' => 'Алексей', 'patronymic' => 'Дмитриевич', 'login' => 'novikov', 'email' => 'novikov@ptpsm.ru', 'phone' => '+7(910)-111-22-33', 'role' => 'Пользователь', 'department' => 'Хозяйственный отдел', 'position' => 'Завхоз', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
    ['surname' => 'Козлова', 'name' => 'Мария', 'patronymic' => 'Игоревна', 'login' => 'kozlova', 'email' => 'kozlova@ptpsm.ru', 'phone' => '+7(911)-444-55-66', 'role' => 'Пользователь', 'department' => 'Отдел продаж', 'position' => 'Менеджер', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
];

$stmtInsert = $db->prepare("INSERT INTO user (surname, name, patronymic, login, email, phone, password, role, department_id, position) 
                            VALUES (:surname, :name, :patronymic, :login, :email, :phone, :password, :role, :dept, :position)");

$userIds = [];
foreach ($usersData as $u) {
    $deptId = $depMap[$u['department']] ?? null;
    $stmtInsert->execute([
        ':surname' => $u['surname'],
        ':name' => $u['name'],
        ':patronymic' => $u['patronymic'],
        ':login' => $u['login'],
        ':email' => $u['email'],
        ':phone' => $u['phone'],
        ':password' => $u['password'],
        ':role' => $u['role'],
        ':dept' => $deptId,
        ':position' => $u['position']
    ]);
    $userId = $db->lastInsertId();
    $userIds[$u['login']] = $userId;
}
echo "✅ Пользователи добавлены (" . count($usersData) . " записей)\n";

// =====================================================
// 5. Заявки (40 штук)
// =====================================================
$statuses = ['Новый', 'В работе', 'Подтвержден', 'Отменен', 'Закрыт'];
$priorityIds = $db->query("SELECT priority_id FROM priorities")->fetchAll(PDO::FETCH_COLUMN);
$categoryIds = $db->query("SELECT category_id FROM categories")->fetchAll(PDO::FETCH_COLUMN);
$allUserIds = array_values($userIds);

$problems = [
    'Не работает принтер в кабинете 210',
    'Зависает компьютер, требуется переустановка ОС',
    'Нет доступа к общей папке на сервере',
    'Не открывается 1С: Предприятие',
    'Прошу настроить электронную почту на новом рабочем месте',
    'Слетела лицензия на антивирус',
    'Не работает проектор в актовом зале',
    'Необходимо установить программу Adobe Reader',
    'Пропал интернет в кабинете 105',
    'Заправка картриджа для принтера HP',
    'Сбой в работе СКУД (пропускная система)',
    'Не хватает оперативной памяти, подвисает AutoCAD',
    'Заявка на подключение нового сотрудника к сети',
    'Нет звука на компьютере, не определяются колонки',
    'Требуется настройка VPN для удалённой работы',
];
$nameOrgs = [
    'Кабинет 101', 'Кабинет 210', 'Библиотека', 'Актовый зал', 'Склад', 'Бухгалтерия', 
    'Приёмная директора', 'Учебный класс 5', 'Лаборатория химии', 'Серверная',
];

$existing = $db->query("SELECT COUNT(*) FROM application")->fetchColumn();
if ($existing < 10) {
    $numApps = 40;
    $appIds = [];
    for ($i = 0; $i < $numApps; $i++) {
        $date = date('Ymd', strtotime("-$i days"));
        $number = "APP-" . $date . "-" . str_pad($i+1, 3, '0', STR_PAD_LEFT);
        $status = randomElement($statuses);
        $priorityId = randomElement($priorityIds);
        $categoryId = !empty($categoryIds) ? randomElement($categoryIds) : null;
        $userId = randomElement($allUserIds);
        $assignedTo = (mt_rand(0, 100) > 30) ? randomElement($allUserIds) : null;
        $nameOrg = randomElement($nameOrgs);
        $message = randomElement($problems);
        $expectedDate = (mt_rand(0, 1)) ? date('Y-m-d', strtotime('+' . mt_rand(1, 30) . ' days')) : null;
        $createdAt = randomDate('2025-01-01', '2026-05-27');
        $closedAt = ($status === 'Закрыт' || $status === 'Отменен') ? randomDate($createdAt, '2026-05-27') : null;
        
        $stmt = $db->prepare("INSERT INTO application (user_id, number, status, category_id, priority_id, assigned_to, name_org, message, expected_date, created_at, closed_at)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $number, $status, $categoryId, $priorityId, $assignedTo, $nameOrg, $message, $expectedDate, $createdAt, $closedAt]);
        $appIds[] = $db->lastInsertId();
    }
    echo "✅ Создано $numApps заявок\n";

    // =====================================================
    // 6. Комментарии
    // =====================================================
    $commentTexts = [
        'Принято в работу', 'Проблема решена', 'Требуется замена комплектующих', 
        'Ожидаем поставку', 'Назначен ответственный', 'Проверьте настройки', 
        'Заявка закрыта', 'Не удалось воспроизвести проблему', 'Исправлено в новой версии ПО'
    ];
    $numComments = 0;
    foreach ($appIds as $appId) {
        $num = mt_rand(0, 3);
        for ($c = 0; $c < $num; $c++) {
            $userId = randomElement($allUserIds);
            $comment = randomElement($commentTexts);
            $createdAt = randomDate('2025-01-01', '2026-05-27');
            $stmt = $db->prepare("INSERT INTO application_comments (application_id, user_id, comment, created_at) VALUES (?, ?, ?, ?)");
            $stmt->execute([$appId, $userId, $comment, $createdAt]);
            $numComments++;
        }
    }
    echo "✅ Добавлено комментариев: $numComments\n";

    // =====================================================
    // 7. История изменений
    // =====================================================
    $historyFields = ['status', 'priority_id', 'assigned_to'];
    $numHistory = 0;
    foreach ($appIds as $appId) {
        $app = $db->query("SELECT status, priority_id, assigned_to FROM application WHERE application_id = $appId")->fetch(PDO::FETCH_ASSOC);
        if (!$app) continue;
        $prevStatus = $app['status'];
        $prevPriority = $app['priority_id'];
        $prevAssigned = $app['assigned_to'];
        $numChanges = mt_rand(0, 4);
        for ($h = 0; $h < $numChanges; $h++) {
            $field = randomElement($historyFields);
            $oldValue = null;
            $newValue = null;
            $userId = randomElement($allUserIds);
            $createdAt = randomDate('2025-01-01', '2026-05-27');
            if ($field == 'status') {
                $oldValue = $prevStatus;
                $possible = array_diff($statuses, [$prevStatus]);
                $newValue = randomElement($possible);
                $prevStatus = $newValue;
            } elseif ($field == 'priority_id') {
                $oldValue = $prevPriority;
                $possible = array_diff($priorityIds, [$prevPriority]);
                $newValue = randomElement($possible);
                $prevPriority = $newValue;
            } elseif ($field == 'assigned_to') {
                $oldValue = $prevAssigned;
                $newValue = randomElement($allUserIds);
                $prevAssigned = $newValue;
            }
            $stmt = $db->prepare("INSERT INTO application_history (application_id, user_id, field_name, old_value, new_value, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$appId, $userId, $field, (string)$oldValue, (string)$newValue, $createdAt]);
            $numHistory++;
        }
        if ($numChanges > 0) {
            $db->prepare("UPDATE application SET status = ?, priority_id = ?, assigned_to = ? WHERE application_id = ?")
               ->execute([$prevStatus, $prevPriority, $prevAssigned, $appId]);
        }
    }
    echo "✅ Добавлено записей истории: $numHistory\n";

    // =====================================================
    // 8. Вложения (виртуальные)
    // =====================================================
    $numAttachments = 0;
    foreach ($appIds as $appId) {
        $num = mt_rand(0, 2);
        for ($a = 0; $a < $num; $a++) {
            $userId = randomElement($allUserIds);
            $filename = 'dummy_' . uniqid() . '.pdf';
            $originalName = 'Документ_' . mt_rand(1,100) . '.pdf';
            $size = mt_rand(1024, 5242880);
            $mime = 'application/pdf';
            $stmt = $db->prepare("INSERT INTO attachments (application_id, user_id, filename, original_name, size, mime) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$appId, $userId, $filename, $originalName, $size, $mime]);
            $numAttachments++;
        }
    }
    echo "✅ Добавлено вложений: $numAttachments (виртуальных, без файлов)\n";
} else {
    echo "ℹ️ Заявки уже существуют, пропускаем генерацию.\n";
}

echo "🌱 Сидирование завершено!\n";