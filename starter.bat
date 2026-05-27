@echo off
chcp 65001 >nul
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
    pause
    exit /b
)

echo Загрузка переменных окружения...
REM Загрузка переменных из .env через PowerShell
for /f "usebackq tokens=*" %%i in (`powershell -Command "Get-Content .env | Where-Object { $_ -match '^[^#]' -and $_ -match '=' } | ForEach-Object { $_.Trim() }"`) do set %%i

REM Создание базы данных через PHP (не зависит от mysql в PATH)
echo Создание базы данных %DB_NAME%...
php -r "
try {
    \$pdo = new PDO('mysql:host=%DB_HOST%;port=%DB_PORT%;charset=%DB_CHARSET%', '%DB_USER%', '%DB_PASS%');
    \$pdo->exec('CREATE DATABASE IF NOT EXISTS `%DB_NAME%` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo \"База данных готова.\n\";
} catch (PDOException \$e) {
    echo \"Ошибка: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"
if errorlevel 1 (
    echo Не удалось создать БД. Проверьте параметры в .env
    pause
    exit /b
)

REM Установка Composer зависимостей
if not exist "vendor\" (
    echo Установка Composer зависимостей...
    composer install --no-interaction
    if errorlevel 1 (
        echo Ошибка Composer install
        pause
        exit /b
    )
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
if errorlevel 1 (
    echo Ошибка сидов
    pause
    exit /b
)

REM Создание папок для вложений и аватаров
if not exist "storage\uploads" mkdir storage\uploads
if not exist "storage\avatars" mkdir storage\avatars

REM Удаление старой ссылки, если существует
if exist "public\storage" (
    echo Удаление старой ссылки public\storage...
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
echo Данные для входа:
echo   Администратор: логин copp, пароль admin123
echo   Пользователи:   логины из сидов, пароль password123
echo.
echo Запустите веб-сервер и откройте http://localhost
pause