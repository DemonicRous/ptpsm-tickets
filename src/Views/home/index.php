<div class="max-w-4xl mx-auto text-center py-12 md:py-20">
    <div class="mx-auto w-20 h-20 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-6">
        <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
        </svg>
    </div>
    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">Система заявок ПТПСМ</h1>
    <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-8">Быстрая и удобная подача заявок на техническую поддержку</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 text-left">
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm">
            <svg class="w-8 h-8 text-blue-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <h3 class="font-semibold text-lg mb-1">Простые заявки</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Создайте заявку за пару минут, укажите проблему и приоритет</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm">
            <svg class="w-8 h-8 text-blue-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <h3 class="font-semibold text-lg mb-1">Быстрый ответ</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Назначение ответственного и отслеживание статуса в реальном времени</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm">
            <svg class="w-8 h-8 text-blue-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <h3 class="font-semibold text-lg mb-1">Статистика</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Администратор видит полную картину по всем заявкам</p>
        </div>
    </div>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="mt-10 flex flex-wrap justify-center gap-4">
            <a href="/auth" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">Войти</a>
            <a href="/register" class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition">Регистрация</a>
        </div>
    <?php else: ?>
        <div class="mt-10">
            <a href="/profile" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Личный кабинет
            </a>
        </div>
    <?php endif; ?>
</div>