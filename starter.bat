@echo off
title Установка системы заявок ПТПСМ
echo ========================================
echo   Установка системы заявок ПТПСМ
echo ========================================
echo.

REM Проверка наличия .env
if not exist ".env" (
    echo Создание .env из .env.example...
    copy .env.example .env
    echo Отредактируйте .env и запустите скрипт снова.
    echo.
    pause
    exit /b
)

REM Загрузка параметров из .env (упрощённый разбор)
for /f "usebackq tokens=*" %%i in ("%cd%\.env") do set %%i

REM Создание базы данных, если не существует
echo Проверка базы данных %DB_NAME%...
mysql -u %DB_USER% -p%DB_PASS% -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if errorlevel 1 (
    echo Ошибка подключения к MySQL. Проверьте параметры в .env
    pause
    exit /b
)
echo База данных готова.

REM Установка Composer зависимостей
if not exist "vendor\" (
    echo Установка Composer зависимостей...
    composer install --no-interaction
) else (
    echo Composer зависимости уже установлены.
)

REM Автозагрузка
composer dump-autoload

REM Миграция
echo Выполнение миграции...
php commands\MigrateCommand.php
if errorlevel 1 (
    echo Ошибка миграции
    pause
    exit /b
)

REM Сиды
echo Заполнение начальными данными...
php commands\SeedCommand.php

REM Папки для вложений и аватаров
if not exist "storage\uploads" mkdir storage\uploads
if not exist "storage\avatars" mkdir storage\avatars

REM Удаление старых ссылок
if exist "public\storage" (
    echo Удаление старой ссылки public\storage...
    rmdir public\storage
)

REM Создание символической ссылки (junction)
echo Создание символической ссылки public\storage на storage...
New-Item -ItemType Junction -Path "public\storage" -Target "storage"
if errorlevel 1 (
    echo Не удалось создать ссылку. Возможно, нужны права администратора.
    echo Выполните вручную: mklink /J public\storage storage
) else (
    echo Ссылка public\storage создана.
)

REM Установка Node.js зависимостей и сборка CSS
if exist "package.json" (
    echo Установка npm зависимостей...
    call npm install --no-audit --no-fund
    echo Сборка CSS...
    call npm run build:css
) else (
    echo package.json не найден, пропуск сборки CSS.
)

echo.
echo ========================================
echo   Установка завершена!
echo ========================================
echo.
echo Для входа в систему:
echo   Администратор: логин copp, пароль admin123
echo   Пользователь:   логин ivan, пароль password123
echo   Другие пользователи: логины из сидов, пароль password123
echo.
echo Запустите веб-сервер и откройте http://localhost
pause