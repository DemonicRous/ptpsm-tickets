<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl md:text-3xl font-bold mb-6">Панель администратора</h1>

    <?php
    $statusCounts = [];
    foreach ($stats['by_status'] as $s) { $statusCounts[$s['status']] = $s['count']; }
    $total = array_sum($statusCounts);
    $newCount = $statusCounts['Новый'] ?? 0;
    $inWork = $statusCounts['В работе'] ?? 0;
    $closed = ($statusCounts['Подтвержден'] ?? 0) + ($statusCounts['Закрыт'] ?? 0);
    ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 flex items-center justify-between"><div><p class="text-gray-500 text-sm">Всего заявок</p><p class="text-3xl font-bold"><?= $total ?></p></div><svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg></div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 flex items-center justify-between"><div><p class="text-gray-500 text-sm">Новые</p><p class="text-3xl font-bold text-blue-600"><?= $newCount ?></p></div><svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 flex items-center justify-between"><div><p class="text-gray-500 text-sm">В работе</p><p class="text-3xl font-bold text-yellow-600"><?= $inWork ?></p></div><svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 flex items-center justify-between"><div><p class="text-gray-500 text-sm">Завершено</p><p class="text-3xl font-bold text-green-600"><?= $closed ?></p></div><svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 mb-6">
        <form method="GET" action="/admin" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div><label class="block text-sm font-medium mb-1">Статус</label><select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700"><option value="">Все</option><option value="Новый" <?= ($filters['status'] ?? '') === 'Новый' ? 'selected' : '' ?>>Новый</option><option value="В работе" <?= ($filters['status'] ?? '') === 'В работе' ? 'selected' : '' ?>>В работе</option><option value="Подтвержден" <?= ($filters['status'] ?? '') === 'Подтвержден' ? 'selected' : '' ?>>Подтвержден</option><option value="Отменен" <?= ($filters['status'] ?? '') === 'Отменен' ? 'selected' : '' ?>>Отменен</option><option value="Закрыт" <?= ($filters['status'] ?? '') === 'Закрыт' ? 'selected' : '' ?>>Закрыт</option></select></div>
            <div><label class="block text-sm font-medium mb-1">Приоритет</label><select name="priority" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700"><option value="">Все</option><?php foreach ($priorities as $p): ?><option value="<?= $p['priority_id'] ?>" <?= (($filters['priority_id'] ?? '') == $p['priority_id']) ? 'selected' : '' ?>><?= htmlspecialchars($p['name']) ?></option><?php endforeach; ?></select></div>
            <div><label class="block text-sm font-medium mb-1">Категория</label><select name="category" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700"><option value="">Все</option><?php foreach ($categories as $c): ?><option value="<?= $c['category_id'] ?>" <?= (($filters['category_id'] ?? '') == $c['category_id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option><?php endforeach; ?></select></div>
            <div><label class="block text-sm font-medium mb-1">Ответственный</label><select name="assigned" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700"><option value="">Любой</option><?php foreach ($users as $u): ?><option value="<?= $u['user_id'] ?>" <?= (($filters['assigned_to'] ?? '') == $u['user_id']) ? 'selected' : '' ?>><?= htmlspecialchars($u['surname'] . ' ' . $u['name']) ?></option><?php endforeach; ?></select></div>
            <div><label class="block text-sm font-medium mb-1">Поиск</label><input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="№, текст, автор" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700"></div>
            <div class="flex items-end"><button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Применить</button></div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">№</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Автор</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Место</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Приоритет</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ответственный</th><th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Действия</th></tr></thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php foreach ($applications as $app): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-4 py-3 text-sm font-medium"><?= htmlspecialchars($app['number']) ?></td>
                        <td class="px-4 py-3 text-sm"><?= date('d.m.Y', strtotime($app['created_at'])) ?></td>
                        <td class="px-4 py-3 text-sm"><?= htmlspecialchars($app['surname'] . ' ' . mb_substr($app['name'],0,1).'.') ?></td>
                        <td class="px-4 py-3 text-sm"><?= htmlspecialchars(mb_strimwidth($app['name_org'],0,30,'...')) ?></td>
                        <td class="px-4 py-3"><span class="badge <?= match($app['status']) { 'Новый'=>'badge-blue', 'В работе'=>'badge-yellow', 'Подтвержден'=>'badge-green', 'Отменен'=>'badge-red', default=>'badge-gray' } ?>"><?= htmlspecialchars($app['status']) ?></span></td>
                        <td class="px-4 py-3"><span class="badge <?= match($app['priority_name'] ?? 'Средний') { 'Низкий'=>'badge-gray', 'Средний'=>'badge-blue', 'Высокий'=>'badge-yellow', 'Критический'=>'badge-red', default=>'badge-gray' } ?>"><?= htmlspecialchars($app['priority_name'] ?? 'Средний') ?></span></td>
                        <td class="px-4 py-3 text-sm"><?= htmlspecialchars($app['assigned_surname'] ?? '—') ?></td>
                        <td class="px-4 py-3 text-center"><a href="/admin/view?id=<?= $app['application_id'] ?>" class="text-blue-600 hover:text-blue-800 inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>Просмотр</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="block md:hidden divide-y divide-gray-200 dark:divide-gray-700">
            <?php foreach ($applications as $app): ?>
            <div class="p-4 space-y-2"><div class="flex justify-between items-start"><span class="font-mono text-sm font-bold">№<?= htmlspecialchars($app['number']) ?></span><span class="badge <?= match($app['status']){'Новый'=>'badge-blue','В работе'=>'badge-yellow','Подтвержден'=>'badge-green','Отменен'=>'badge-red',default=>'badge-gray'} ?>"><?= htmlspecialchars($app['status']) ?></span></div><p class="text-xs text-gray-500"><?= date('d.m.Y H:i', strtotime($app['created_at'])) ?> • <?= htmlspecialchars($app['surname'] ?? '') ?></p><p class="text-sm"><strong>Место:</strong> <?= htmlspecialchars($app['name_org']) ?></p><p class="text-sm line-clamp-2"><?= nl2br(htmlspecialchars($app['message'])) ?></p><div class="flex justify-between items-center pt-1"><span class="text-xs text-gray-500">Отв: <?= htmlspecialchars($app['assigned_surname'] ?? '—') ?></span><a href="/admin/view?id=<?= $app['application_id'] ?>" class="text-blue-600 text-sm font-medium">Подробнее →</a></div></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>