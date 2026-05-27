<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <h1 class="text-2xl font-bold">Редактирование профиля</h1>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg"><?php foreach ($errors as $error) echo "<p>".htmlspecialchars($error)."</p>"; ?></div>
        <?php endif; ?>

        <form method="POST" action="/profile/update">
            <?= \Core\CSRF::input() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div><label class="block text-sm font-medium mb-1">Фамилия *</label><input type="text" name="surname" value="<?= htmlspecialchars($old['surname'] ?? $user['surname'] ?? '') ?>" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Имя *</label><input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? $user['name'] ?? '') ?>" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Отчество *</label><input type="text" name="patronymic" value="<?= htmlspecialchars($old['patronymic'] ?? $user['patronymic'] ?? '') ?>" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Email *</label><input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? $user['email'] ?? '') ?>" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Телефон *</label><input type="tel" name="phone" value="<?= htmlspecialchars($old['phone'] ?? $user['phone'] ?? '') ?>" placeholder="+7(123)-456-78-90" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Новый пароль (оставьте пустым, если не менять)</label><input type="password" name="password" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
                <div><label class="block text-sm font-medium mb-1">Повторите пароль</label><input type="password" name="password_repeat" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700"></div>
            </div>
            <button type="submit" class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">Сохранить изменения</button>
        </form>

        <!-- Форма загрузки аватара -->
        <div class="mt-8 pt-6 border-t dark:border-gray-700">
            <h2 class="text-xl font-semibold mb-4">Аватар</h2>
            <div class="flex items-center gap-6 flex-wrap">
                <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <?php if (!empty($user['avatar']) && file_exists(__DIR__ . '/../../../storage/avatars/' . $user['avatar'])): ?>
                        <img src="/storage/avatars/<?= htmlspecialchars($user['avatar']) ?>" alt="Аватар" class="w-full h-full object-cover">
                    <?php else: ?>
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <?php endif; ?>
                </div>
                <form method="POST" action="/profile/upload-avatar" enctype="multipart/form-data" class="flex-1">
                    <?= \Core\CSRF::input() ?>
                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" required class="mb-2 text-sm">
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition">Загрузить аватар</button>
                </form>
            </div>
            <p class="text-xs text-gray-500 mt-2">Рекомендуемый размер: 200x200 пикселей. Форматы: JPG, PNG, GIF, WebP.</p>
        </div>
    </div>
</div>