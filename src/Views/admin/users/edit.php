<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            <h1 class="text-2xl font-bold">Редактирование пользователя</h1>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg"><?php foreach ($errors as $error) echo "<p>".htmlspecialchars($error)."</p>"; ?></div>
        <?php endif; ?>

        <form method="POST" action="/admin/users/update">
            <?= \Core\CSRF::input() ?>
            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div><label class="block text-sm font-medium mb-1">Фамилия *</label><input type="text" name="surname" value="<?= htmlspecialchars($old['surname'] ?? $user['surname'] ?? '') ?>" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Имя *</label><input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? $user['name'] ?? '') ?>" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Отчество *</label><input type="text" name="patronymic" value="<?= htmlspecialchars($old['patronymic'] ?? $user['patronymic'] ?? '') ?>" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Логин *</label><input type="text" name="login" value="<?= htmlspecialchars($old['login'] ?? $user['login'] ?? '') ?>" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Email *</label><input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? $user['email'] ?? '') ?>" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Телефон *</label><input type="tel" name="phone" value="<?= htmlspecialchars($old['phone'] ?? $user['phone'] ?? '') ?>" placeholder="+7(123)-456-78-90" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Роль</label>
                    <select name="role" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700">
                        <option value="Пользователь" <?= (($old['role'] ?? $user['role']) == 'Пользователь') ? 'selected' : '' ?>>Пользователь</option>
                        <option value="Администратор" <?= (($old['role'] ?? $user['role']) == 'Администратор') ? 'selected' : '' ?>>Администратор</option>
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-1">Отдел</label>
                    <select name="department_id" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700">
                        <option value="">-- Не выбран --</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['department_id'] ?>" <?= (($old['department_id'] ?? $user['department_id']) == $dept['department_id']) ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-1">Должность</label>
                    <input type="text" name="position" value="<?= htmlspecialchars($old['position'] ?? $user['position'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700">
                </div>
                <div><label class="block text-sm font-medium mb-1">Новый пароль (оставьте пустым, если не менять)</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700">
                </div>
                <div><label class="block text-sm font-medium mb-1">Повторите пароль</label>
                    <input type="password" name="password_repeat" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700">
                </div>
            </div>
            <button type="submit" class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">Сохранить изменения</button>
        </form>
    </div>
</div>