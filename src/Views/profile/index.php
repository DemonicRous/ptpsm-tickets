<div class="max-w-5xl mx-auto">
    <h1 class="text-2xl md:text-3xl font-bold mb-6">Личный кабинет</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Карточка профиля -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 sticky top-20">
                <div class="text-center">
                    <!-- Аватар с проверкой существования файла -->
                    <div class="w-24 h-24 mx-auto bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center overflow-hidden">
                        <?php 
                        $avatarPath = !empty($user['avatar']) ? '/storage/avatars/' . $user['avatar'] : '';
                        $fullAvatarPath = __DIR__ . '/../../../storage/avatars/' . ($user['avatar'] ?? '');
                        if (!empty($user['avatar']) && file_exists($fullAvatarPath)): ?>
                            <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Аватар" class="w-full h-full object-cover">
                        <?php else: ?>
                            <svg class="w-12 h-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                    
                    <h2 class="text-xl font-semibold mt-3"><?= htmlspecialchars($user['surname'] . ' ' . $user['name'] . ' ' . $user['patronymic']) ?></h2>
                    <p class="text-sm text-gray-500">@<?= htmlspecialchars($user['login']) ?></p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $user['role'] === 'Администратор' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' ?>">
                            <?= htmlspecialchars($user['role']) ?>
                        </span>
                    </div>
                    <a href="/profile/edit" class="mt-3 inline-block text-sm text-blue-600 hover:underline">Редактировать профиль</a>
                </div>
                <div class="border-t dark:border-gray-700 my-4"></div>
                <div class="space-y-2 text-sm">
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <?= htmlspecialchars($user['email']) ?>
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <?= htmlspecialchars($user['phone']) ?>
                    </p>
                    <?php if (!empty($user['department_name'])): ?>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <?= htmlspecialchars($user['department_name']) ?>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($user['position'])): ?>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <?= htmlspecialchars($user['position']) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Мои заявки -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <div class="flex flex-wrap justify-between items-center mb-5">
                    <h2 class="text-xl font-semibold">Мои заявки</h2>
                    <a href="/application/create" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Новая заявка
                    </a>
                </div>

                <?php if (empty($applications)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p>У вас пока нет заявок</p>
                        <a href="/application/create" class="mt-3 inline-block text-blue-600 hover:underline">Создать первую заявку</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($applications as $app): ?>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <div class="flex flex-wrap justify-between items-start gap-2">
                                    <div>
                                        <h3 class="font-semibold">Заявка №<?= htmlspecialchars($app['number']) ?></h3>
                                        <p class="text-xs text-gray-500"><?= date('d.m.Y H:i', strtotime($app['created_at'])) ?></p>
                                    </div>
                                    <?php
                                    $priorityClass = match($app['priority_name'] ?? 'Средний') {
                                        'Низкий' => 'badge-gray',
                                        'Средний' => 'badge-blue',
                                        'Высокий' => 'badge-yellow',
                                        'Критический' => 'badge-red',
                                        default => 'badge-gray'
                                    };
                                    ?>
                                    <span class="badge <?= $priorityClass ?>"><?= htmlspecialchars($app['priority_name'] ?? 'Средний') ?></span>
                                </div>
                                <p class="mt-2 text-sm"><strong>Место:</strong> <?= htmlspecialchars($app['name_org']) ?></p>
                                <p class="text-sm line-clamp-2"><?= nl2br(htmlspecialchars($app['message'])) ?></p>
                                <div class="mt-3 flex justify-between items-center">
                                    <?php
                                    $statusClass = match($app['status']) {
                                        'Новый' => 'badge-blue',
                                        'В работе' => 'badge-yellow',
                                        'Подтвержден' => 'badge-green',
                                        'Отменен' => 'badge-red',
                                        'Закрыт' => 'badge-gray',
                                        default => 'badge-gray'
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($app['status']) ?></span>
                                    <a href="/application/view?id=<?= $app['application_id'] ?>" class="text-blue-600 text-sm hover:underline flex items-center gap-1">
                                        Подробнее
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                                <?php if (!empty($app['assigned_surname'])): ?>
                                    <p class="text-xs text-gray-500 mt-2">Ответственный: <?= htmlspecialchars($app['assigned_surname']) ?> <?= htmlspecialchars($app['assigned_name'] ?? '') ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>