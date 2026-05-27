<?php
/** @var string $content */
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ПТПСМ: Заявки</title>
    <script>
        // Установка темы до загрузки стилей
        (function() {
            const theme = localStorage.getItem('ptpsm-theme') || 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
    <link href="/assets/css/output.css" rel="stylesheet">
    <script src="/assets/js/theme.js" defer></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <header class="bg-white dark:bg-gray-800 shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600 dark:text-blue-400">ПТПСМ: Заявки</a>
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'Администратор'): ?>
                        <a href="/admin" class="hover:underline">Админ-панель</a>
                    <?php endif; ?>
                    <a href="/profile" class="hover:underline">Профиль</a>
                    <a href="/logout" class="hover:underline">Выйти</a>
                <?php else: ?>
                    <a href="/auth" class="hover:underline">Вход</a>
                    <a href="/register" class="hover:underline">Регистрация</a>
                <?php endif; ?>
                <button id="theme-toggle" class="p-2 rounded-full bg-gray-200 dark:bg-gray-700">
                    🌓
                </button>
            </div>
        </div>
    </header>
    <main class="container mx-auto px-4 py-8 min-h-[calc(100vh-140px)]">
        <?= $content ?? '' ?>
    </main>
    <footer class="bg-white dark:bg-gray-800 text-center py-4 text-sm border-t dark:border-gray-700">
        &copy; ПТПСМ 2026. Система заявок.
    </footer>
</body>
</html>