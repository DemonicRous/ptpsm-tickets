<?php
/** @var array $applications */
/** @var array $users */
/** @var array $categories */
/** @var array $priorities */
/** @var array $filters */
/** @var array $stats */
?>

<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Панель администратора</h1>

    <!-- Статистика -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <?php foreach ($stats['by_status'] as $stat): ?>
            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                <div class="text-2xl font-bold"><?= $stat['count'] ?></div>
                <div class="text-gray-600 dark:text-gray-300"><?= htmlspecialchars($stat['status']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Фильтры -->
    <form method="GET" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <input type="text" name="search" placeholder="Поиск по №, тексту, фамилии" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" class="border rounded px-3 py-2 dark:bg-gray-700">
            <select name="status" class="border rounded px-3 py-2 dark:bg-gray-700">
                <option value="">Все статусы</option>
                <option value="Новый" <?= ($filters['status'] ?? '') == 'Новый' ? 'selected' : '' ?>>Новый</option>
                <option value="В работе" <?= ($filters['status'] ?? '') == 'В работе' ? 'selected' : '' ?>>В работе</option>
                <option value="Подтвержден" <?= ($filters['status'] ?? '') == 'Подтвержден' ? 'selected' : '' ?>>Подтвержден</option>
                <option value="Отменен" <?= ($filters['status'] ?? '') == 'Отменен' ? 'selected' : '' ?>>Отменен</option>
                <option value="Закрыт" <?= ($filters['status'] ?? '') == 'Закрыт' ? 'selected' : '' ?>>Закрыт</option>
            </select>
            <select name="priority" class="border rounded px-3 py-2 dark:bg-gray-700">
                <option value="">Все приоритеты</option>
                <?php foreach ($priorities as $pri): ?>
                    <option value="<?= $pri['priority_id'] ?>" <?= ($filters['priority_id'] ?? '') == $pri['priority_id'] ? 'selected' : '' ?>><?= htmlspecialchars($pri['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="category" class="border rounded px-3 py-2 dark:bg-gray-700">
                <option value="">Все категории</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>" <?= ($filters['category_id'] ?? '') == $cat['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="assigned" class="border rounded px-3 py-2 dark:bg-gray-700">
                <option value="">Все ответственные</option>
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u['user_id'] ?>" <?= ($filters['assigned_to'] ?? '') == $u['user_id'] ? 'selected' : '' ?>><?= htmlspecialchars($u['surname'] . ' ' . $u['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Фильтровать</button>
        </div>
    </form>

    <!-- Список заявок -->
    <div class="space-y-4">
        <?php foreach ($applications as $app): ?>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 <?= $app['status'] === 'Отменен' ? 'opacity-75' : '' ?>">
                <div class="flex justify-between items-start flex-wrap">
                    <div>
                        <h2 class="text-xl font-semibold">
                            <a href="/admin/view?id=<?= $app['application_id'] ?>" class="hover:underline">Заявка №<?= htmlspecialchars($app['number']) ?></a>
                        </h2>
                        <p class="text-sm text-gray-500">от <?= htmlspecialchars($app['surname'] . ' ' . $app['name'] . ' ' . $app['patronymic']) ?></p>
                    </div>
                    <span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: <?= htmlspecialchars($app['color'] ?? '#ccc') ?>20; color: <?= htmlspecialchars($app['color'] ?? '#000') ?>">
                        <?= htmlspecialchars($app['priority_name'] ?? 'Средний') ?>
                    </span>
                </div>

                <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <div><strong>Статус:</strong> <?= htmlspecialchars($app['status']) ?></div>
                    <div><strong>Категория:</strong> <?= htmlspecialchars($app['category_name'] ?? '—') ?></div>
                    <div><strong>Ответственный:</strong> <?= htmlspecialchars($app['assigned_surname'] ?? '—') ?> <?= htmlspecialchars($app['assigned_name'] ?? '') ?></div>
                    <div><strong>Кабинет:</strong> <?= htmlspecialchars($app['name_org']) ?></div>
                </div>
                <div class="mt-2"><?= nl2br(htmlspecialchars(mb_substr($app['message'], 0, 150))) ?>...</div>

                <div class="mt-3 flex flex-wrap gap-2">
                    <form method="POST" action="/admin/update-status" class="inline">
                        <?= \Core\CSRF::input() ?>
                        <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                        <select name="status" class="border rounded px-2 py-1 text-sm dark:bg-gray-700">
                            <option value="Новый" <?= $app['status'] == 'Новый' ? 'selected' : '' ?>>Новый</option>
                            <option value="В работе" <?= $app['status'] == 'В работе' ? 'selected' : '' ?>>В работе</option>
                            <option value="Подтвержден" <?= $app['status'] == 'Подтвержден' ? 'selected' : '' ?>>Подтвержден</option>
                            <option value="Отменен" <?= $app['status'] == 'Отменен' ? 'selected' : '' ?>>Отменен</option>
                            <option value="Закрыт" <?= $app['status'] == 'Закрыт' ? 'selected' : '' ?>>Закрыт</option>
                        </select>
                        <button type="submit" class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700">Изменить</button>
                    </form>

                    <form method="POST" action="/admin/assign" class="inline">
                        <?= \Core\CSRF::input() ?>
                        <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                        <select name="assigned_to" class="border rounded px-2 py-1 text-sm dark:bg-gray-700">
                            <option value="">-- Назначить --</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['user_id'] ?>" <?= ($app['assigned_to'] == $u['user_id']) ? 'selected' : '' ?>><?= htmlspecialchars($u['surname'] . ' ' . $u['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700">Назначить</button>
                    </form>

                    <a href="/admin/view?id=<?= $app['application_id'] ?>" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Детали</a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($applications)): ?>
            <div class="bg-white dark:bg-gray-800 p-8 text-center rounded shadow">Нет заявок по заданным фильтрам.</div>
        <?php endif; ?>
    </div>
</div>