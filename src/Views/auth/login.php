<div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Вход</h2>
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">Неверный логин или пароль</div>
    <?php endif; ?>
    <form method="POST">
        <?= \Core\CSRF::input() ?>
        <div class="mb-4">
            <label class="block mb-1">Логин</label>
            <input type="text" name="login" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Пароль</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Войти</button>
    </form>
    <p class="mt-4 text-center">Нет аккаунта? <a href="/register" class="text-blue-600">Регистрация</a></p>
</div>