<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Личный кабинет</h1>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8">
        <h2 class="text-xl font-semibold mb-4">Мои данные</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p><span class="font-semibold">Фамилия:</span> <?= htmlspecialchars($user['surname']) ?></p>
            <p><span class="font-semibold">Имя:</span> <?= htmlspecialchars($user['name']) ?></p>
            <p><span class="font-semibold">Отчество:</span> <?= htmlspecialchars($user['patronymic']) ?></p>
            <p><span class="font-semibold">Логин:</span> <?= htmlspecialchars($user['login']) ?></p>
            <p><span class="font-semibold">Email:</span> <?= htmlspecialchars($user['email']) ?></p>
            <p><span class="font-semibold">Телефон:</span> <?= htmlspecialchars($user['phone']) ?></p>
            <?php if (!empty($user['department_name'])): ?>
                <p><span class="font-semibold">Отдел:</span> <?= htmlspecialchars($user['department_name']) ?></p>
            <?php endif; ?>
            <?php if (!empty($user['position'])): ?>
                <p><span class="font-semibold">Должность:</span> <?= htmlspecialchars($user['position']) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Мои заявки</h2>
            <a href="/application/create" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">+ Новая заявка</a>
        </div>

        <?php if (empty($applications)): ?>
            <p class="text-gray-500">У вас пока нет заявок.</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($applications as $app): ?>
                    <div class="border rounded-lg p-4 <?= $app['status'] === 'Отменен' ? 'bg-gray-100 dark:bg-gray-700' : ($app['status'] === 'Подтвержден' ? 'bg-green-50 dark:bg-green-900' : '') ?>">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold">Заявка №<?= htmlspecialchars($app['number']) ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300"><?= date('d.m.Y H:i', strtotime($app['created_at'])) ?></p>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: <?= htmlspecialchars($app['color'] ?? '#ccc') ?>20; color: <?= htmlspecialchars($app['color'] ?? '#000') ?>">
                                <?= htmlspecialchars($app['priority_name'] ?? 'Средний') ?>
                            </span>
                        </div>
                        <p class="mt-2"><strong>Место:</strong> <?= htmlspecialchars($app['name_org']) ?></p>
                        <p><?= nl2br(htmlspecialchars($app['message'])) ?></p>
                        <p class="mt-1 text-sm"><strong>Статус:</strong> 
                            <span class="font-semibold">
                                <?php
                                $statusClass = match($app['status']) {
                                    'Новый' => 'text-blue-600',
                                    'В работе' => 'text-yellow-600',
                                    'Подтвержден' => 'text-green-600',
                                    'Отменен' => 'text-red-600',
                                    'Закрыт' => 'text-gray-600',
                                    default => ''
                                };
                                ?>
                                <span class="<?= $statusClass ?>"><?= htmlspecialchars($app['status']) ?></span>
                            </span>
                        </p>
                        <?php if (!empty($app['assigned_surname'])): ?>
                            <p class="text-sm text-gray-500">Ответственный: <?= htmlspecialchars($app['assigned_surname']) ?> <?= htmlspecialchars($app['assigned_name'] ?? '') ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>