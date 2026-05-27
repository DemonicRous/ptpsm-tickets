@echo off
chcp 65001 >nul
title Setup PTPSM Tickets System
echo ========================================
echo   PTPSM Tickets System Setup
echo ========================================
echo.

REM Проверка наличия .env
if not exist ".env" (
    echo Creating .env from .env.example...
    copy .env.example .env
    echo Please edit .env and run the script again.
    echo.
    pause
    exit /b
)

REM Загрузка переменных из .env с помощью PowerShell (более надёжно)
echo Loading environment variables...
for /f "usebackq tokens=*" %%i in (`powershell -Command "Get-Content .env | Where-Object { $_ -match '^[^#]' -and $_ -match '=' } | ForEach-Object { $_.Trim() }"`) do set %%i

REM Создание базы данных через PHP (не зависит от mysql в PATH)
echo Creating database %DB_NAME% if not exists...
php -r "
try {
    \$pdo = new PDO('mysql:host=%DB_HOST%;port=%DB_PORT%;charset=%DB_CHARSET%', '%DB_USER%', '%DB_PASS%');
    \$pdo->exec('CREATE DATABASE IF NOT EXISTS `%DB_NAME%` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo \"Database ready.\n\";
} catch (PDOException \$e) {
    echo \"ERROR: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"
if errorlevel 1 (
    echo Failed to create database. Check your .env settings.
    pause
    exit /b
)

REM Установка Composer зависимостей
if not exist "vendor\" (
    echo Installing Composer dependencies...
    composer install --no-interaction
    if errorlevel 1 (
        echo Ошибка Composer install
        pause
        exit /b
    )
) else (
    echo Composer dependencies already installed.
)

REM Автозагрузка
composer dump-autoload

REM Миграция
echo Running migrations...
php commands\MigrateCommand.php
if errorlevel 1 (
    echo Migration failed
    pause
    exit /b
)

REM Сиды
echo Seeding data...
php commands\SeedCommand.php
if errorlevel 1 (
    echo Ошибка сидов
    pause
    exit /b
)

REM Создание папок
if not exist "storage\uploads" mkdir storage\uploads
if not exist "storage\avatars" mkdir storage\avatars

REM Удаление старой ссылки
if exist "public\storage" (
    echo Removing old junction public\storage...
    rmdir public\storage
)

REM Создание junction через PowerShell (так как mklink может не работать)
echo Создание junction public\storage ^<--^> storage...
powershell -Command "New-Item -ItemType Junction -Path 'public\storage' -Target 'storage' -Force" >nul 2>&1
if errorlevel 1 (
    echo Не удалось создать junction. Выполните вручную от администратора:
    echo powershell -Command "New-Item -ItemType Junction -Path 'public\storage' -Target 'storage' -Force"
) else (
    echo Junction создан.
)

REM Сборка CSS (если есть package.json)
if exist "package.json" (
    echo Installing npm dependencies...
    call npm install --no-audit --no-fund
    echo Building CSS...
    call npm run build:css
) else (
    echo package.json not found, skipping CSS build.
)

echo.
echo ========================================
echo   Setup completed!
echo ========================================
echo.
echo Login credentials:
echo   Admin:    login copp, password admin123
echo   Users:    logins from seeders, password password123
echo.
echo Start your web server and open http://localhost
pause
