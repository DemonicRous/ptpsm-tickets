<div class="max-w-md mx-auto mt-10">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
        <div class="text-center mb-6">
            <div class="mx-auto w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            </div>
            <h2 class="text-2xl font-bold mt-4">Вход в систему</h2>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'empty'): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">Логин и пароль обязательны</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">Неверный логин или пароль</div>
        <?php endif; ?>

        <form method="POST">
            <?= \Core\CSRF::input() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Логин</label>
                <input type="text" name="login" required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Пароль</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Войти
            </button>
        </form>
        <p class="text-center text-sm mt-6">Нет аккаунта? <a href="/register" class="text-blue-600 hover:underline">Зарегистрироваться</a></p>
    </div>
</div>