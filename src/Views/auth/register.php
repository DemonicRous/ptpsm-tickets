<?php
/** @var string $csrf_token */
/** @var array $departments */
/** @var array $old */
/** @var array $errors */
?>

<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Регистрация</h2>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php foreach ($errors as $field => $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <?= \Core\CSRF::input() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1">Фамилия *</label>
                <input type="text" name="surname" value="<?= htmlspecialchars($old['surname'] ?? '') ?>" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required>
            </div>
            <div>
                <label class="block mb-1">Имя *</label>
                <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required>
            </div>
            <div>
                <label class="block mb-1">Отчество *</label>
                <input type="text" name="patronymic" value="<?= htmlspecialchars($old['patronymic'] ?? '') ?>" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required>
            </div>
            <div>
                <label class="block mb-1">Логин *</label>
                <input type="text" name="login" value="<?= htmlspecialchars($old['login'] ?? '') ?>" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required>
            </div>
            <div>
                <label class="block mb-1">Email *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required>
            </div>
            <div>
                <label class="block mb-1">Телефон *</label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" placeholder="+7(123)-456-78-90" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required>
                <p class="text-xs text-gray-500 mt-1">Формат: цифры, скобки, дефисы. Минимум 10 цифр.</p>
            </div>
            <div>
                <label class="block mb-1">Отдел</label>
                <select name="department_id" class="w-full border rounded px-3 py-2 dark:bg-gray-700">
                    <option value="">-- Не выбран --</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['department_id'] ?>" <?= (($old['department_id'] ?? '') == $dept['department_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-1">Должность</label>
                <input type="text" name="position" value="<?= htmlspecialchars($old['position'] ?? '') ?>" class="w-full border rounded px-3 py-2 dark:bg-gray-700">
            </div>
            <div>
                <label class="block mb-1">Пароль * (мин. 6 символов)</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required>
            </div>
            <div>
                <label class="block mb-1">Повторите пароль *</label>
                <input type="password" name="password_repeat" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required>
            </div>
        </div>

        <button type="submit" class="mt-6 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Зарегистрироваться</button>
    </form>
    <p class="mt-4 text-center">Уже есть аккаунт? <a href="/auth" class="text-blue-600">Войти</a></p>
</div>