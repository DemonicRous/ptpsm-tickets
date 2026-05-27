@echo off
chcp 65001 >nul
title Установка системы заявок ПТПСМ
echo ========================================
echo   Установка системы заявок ПТПСМ
echo ========================================
echo.

if not exist ".env" (
    echo Создание .env из .env.example...
    copy .env.example .env
    echo Отредактируйте .env и запустите скрипт снова.
    pause
    exit /b
)

echo Загрузка настроек из .env...
for /f "usebackq tokens=*" %%i in (`powershell -Command "Get-Content .env | Where-Object { $_ -match '^[^#]' -and $_ -match '=' } | ForEach-Object { $_.Trim() }"`) do set %%i

echo Создание базы данных %DB_NAME%...
php setup_db.php
if errorlevel 1 (
    echo Не удалось создать БД. Проверьте параметры в .env
    pause
    exit /b
)

echo Выполнение миграции...
php commands\MigrateCommand.php
if errorlevel 1 (
    echo Ошибка миграции
    pause
    exit /b
)

echo Заполнение начальными данными...
php commands\SeedCommand.php
if errorlevel 1 (
    echo Ошибка сидов
    pause
    exit /b
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
pause