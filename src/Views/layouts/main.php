<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ПТПСМ: Заявки</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link href="/assets/css/output.css" rel="stylesheet">
    <script src="https://unpkg.com/heroicons@2.0.18/24/outline/index.js"></script>
    <style>
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-blue { background-color: #dbeafe; color: #1e40af; }
        .dark .badge-blue { background-color: #1e3a8a; color: #bfdbfe; }
        .badge-green { background-color: #dcfce7; color: #166534; }
        .dark .badge-green { background-color: #14532d; color: #bbf7d0; }
        .badge-red { background-color: #fee2e2; color: #991b1b; }
        .dark .badge-red { background-color: #7f1d1d; color: #fecaca; }
        .badge-yellow { background-color: #fef9c3; color: #854d0e; }
        .dark .badge-yellow { background-color: #713f12; color: #fef08a; }
        .badge-gray { background-color: #e5e7eb; color: #1f2937; }
        .dark .badge-gray { background-color: #374151; color: #e5e7eb; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased flex flex-col min-h-screen">

<header class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-30">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <a href="/" class="text-xl md:text-2xl font-bold text-blue-600 dark:text-blue-400 flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
            ПТПСМ Заявки
        </a>

        <!-- Десктопное меню -->
        <nav class="hidden md:flex items-center gap-6">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] === 'Администратор'): ?>
                    <a href="/admin" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Админ
                    </a>
                    <a href="/admin/users" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Пользователи
                    </a>
                <?php endif; ?>
                <a href="/profile" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 transition flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Профиль
                </a>
                <a href="/logout" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 transition flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Выйти
                </a>
            <?php else: ?>
                <a href="/auth" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 transition flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Вход
                </a>
                <a href="/register" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 transition flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Регистрация
                </a>
            <?php endif; ?>
        </nav>

        <div class="flex items-center gap-2">
            <button id="theme-toggle" class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>
            <button id="burger-btn" class="md:hidden p-2 rounded-lg bg-gray-100 dark:bg-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <!-- Мобильное меню -->
    <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-800 border-t dark:border-gray-700 py-2 px-4">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role'] === 'Администратор'): ?>
                <a href="/admin" class="flex items-center gap-2 py-2 px-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Админ-панель</a>
                <a href="/admin/users" class="flex items-center gap-2 py-2 px-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Пользователи</a>
            <?php endif; ?>
            <a href="/profile" class="flex items-center gap-2 py-2 px-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Профиль</a>
            <a href="/logout" class="flex items-center gap-2 py-2 px-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Выйти</a>
        <?php else: ?>
            <a href="/auth" class="flex items-center gap-2 py-2 px-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Вход</a>
            <a href="/register" class="flex items-center gap-2 py-2 px-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Регистрация</a>
        <?php endif; ?>
    </div>
</header>

<main class="container mx-auto px-4 py-6 md:py-8 flex-grow">
    <?php if (isset($_GET['success']) && $_GET['success'] === 'created'): ?>
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">✅ Заявка успешно создана.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">⚠️ Ошибка: <?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <?= $content ?? '' ?>
</main>

<footer class="bg-white dark:bg-gray-800 border-t dark:border-gray-700 text-center py-4 text-sm text-gray-500">
    &copy; <?= date('Y') ?> ПТПСМ — Система заявок
</footer>

<script>
    (function() {
        const theme = localStorage.getItem('ptpsm-theme') || 'light';
        if (theme === 'dark') document.documentElement.classList.add('dark');
    })();
    const toggleBtn = document.getElementById('theme-toggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            html.classList.toggle('dark', !isDark);
            localStorage.setItem('ptpsm-theme', isDark ? 'light' : 'dark');
        });
    }
    const burger = document.getElementById('burger-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (burger && mobileMenu) {
        burger.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
</script>
</body>
</html>