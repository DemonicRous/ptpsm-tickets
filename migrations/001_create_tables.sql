-- =====================================================
-- 1. Отделы
-- =====================================================
CREATE TABLE IF NOT EXISTS `departments` (
    `department_id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT,
    PRIMARY KEY (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. Пользователи (расширенные)
-- =====================================================
CREATE TABLE IF NOT EXISTS `user` (
    `user_id` INT NOT NULL AUTO_INCREMENT,
    `surname` VARCHAR(100) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `patronymic` VARCHAR(100) NOT NULL,
    `login` VARCHAR(100) NOT NULL UNIQUE,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('Пользователь', 'Администратор') NOT NULL DEFAULT 'Пользователь',
    `department_id` INT NULL,
    `position` VARCHAR(100) NULL,
    `avatar` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    FOREIGN KEY (`department_id`) REFERENCES `departments`(`department_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. Категории заявок (иерархические)
-- =====================================================
CREATE TABLE IF NOT EXISTS `categories` (
    `category_id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `parent_id` INT NULL,
    `description` TEXT,
    PRIMARY KEY (`category_id`),
    FOREIGN KEY (`parent_id`) REFERENCES `categories`(`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. Приоритеты
-- =====================================================
CREATE TABLE IF NOT EXISTS `priorities` (
    `priority_id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `color` VARCHAR(20) NOT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    PRIMARY KEY (`priority_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 5. Заявки
-- =====================================================
CREATE TABLE IF NOT EXISTS `application` (
    `application_id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `number` VARCHAR(30) NOT NULL UNIQUE,
    `status` ENUM('Новый', 'В работе', 'Подтвержден', 'Отменен', 'Закрыт') NOT NULL DEFAULT 'Новый',
    `category_id` INT NULL,
    `priority_id` INT NOT NULL DEFAULT 2,
    `assigned_to` INT NULL,
    `name_org` TEXT NOT NULL,
    `message` TEXT NOT NULL,
    `expected_date` DATE NULL,
    `closed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`application_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`category_id`) ON DELETE SET NULL,
    FOREIGN KEY (`priority_id`) REFERENCES `priorities`(`priority_id`),
    FOREIGN KEY (`assigned_to`) REFERENCES `user`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 6. Комментарии
-- =====================================================
CREATE TABLE IF NOT EXISTS `application_comments` (
    `comment_id` INT NOT NULL AUTO_INCREMENT,
    `application_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `comment` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`comment_id`),
    FOREIGN KEY (`application_id`) REFERENCES `application`(`application_id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 7. История изменений
-- =====================================================
CREATE TABLE IF NOT EXISTS `application_history` (
    `history_id` INT NOT NULL AUTO_INCREMENT,
    `application_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `field_name` VARCHAR(50) NOT NULL,
    `old_value` VARCHAR(255) NULL,
    `new_value` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`history_id`),
    FOREIGN KEY (`application_id`) REFERENCES `application`(`application_id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 8. Вложения
-- =====================================================
CREATE TABLE IF NOT EXISTS `attachments` (
    `attachment_id` INT NOT NULL AUTO_INCREMENT,
    `application_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `filename` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `size` INT NOT NULL,
    `mime` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`attachment_id`),
    FOREIGN KEY (`application_id`) REFERENCES `application`(`application_id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 9. Уведомления
-- =====================================================
CREATE TABLE IF NOT EXISTS `notifications` (
    `notification_id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `application_id` INT NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`notification_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`application_id`) REFERENCES `application`(`application_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;