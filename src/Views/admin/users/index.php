<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold">Управление пользователями</h1>
        <a href="/admin/users/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Добавить пользователя
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            <?php if ($_GET['success'] == 'created'): ?>✅ Пользователь создан
            <?php elseif ($_GET['success'] == 'updated'): ?>✅ Пользователь обновлён
            <?php elseif ($_GET['success'] == 'deleted'): ?>✅ Пользователь удалён
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error']) && $_GET['error'] == 'self_delete'): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">❌ Нельзя удалить самого себя</div>
    <?php endif; ?>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ФИО</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Логин</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Телефон</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Роль</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Отдел</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Действия</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-4 py-3 text-sm"><?= $user['user_id'] ?></td>
                        <td class="px-4 py-3 text-sm"><?= htmlspecialchars($user['surname'] . ' ' . $user['name'] . ' ' . $user['patronymic']) ?></td>
                        <td class="px-4 py-3 text-sm"><?= htmlspecialchars($user['login']) ?></td>
                        <td class="px-4 py-3 text-sm"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-4 py-3 text-sm"><?= htmlspecialchars($user['phone']) ?></td>
                        <td class="px-4 py-3 text-sm">
                            <span class="badge <?= $user['role'] === 'Администратор' ? 'badge-red' : 'badge-blue' ?>">
                                <?= htmlspecialchars($user['role']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm"><?= htmlspecialchars($user['department_name'] ?? '—') ?></td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <a href="/admin/users/edit?id=<?= $user['user_id'] ?>" class="text-blue-600 hover:text-blue-800 inline-flex items-center gap-1 mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Ред.
                            </a>
                            <form method="POST" action="/admin/users/delete" class="inline" onsubmit="return confirm('Удалить пользователя?')">
                                <?= \Core\CSRF::input() ?>
                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                <button type="submit" class="text-red-600 hover:text-red-800 inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>