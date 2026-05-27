<div class="max-w-4xl mx-auto">
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">Действие выполнено успешно</div>
    <?php endif; ?>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start">
            <h1 class="text-2xl font-bold">Заявка №<?= htmlspecialchars($application['number']) ?></h1>
            <span class="px-3 py-1 rounded text-sm font-semibold" style="background-color: <?= htmlspecialchars($application['priority_color'] ?? '#ccc') ?>20; color: <?= htmlspecialchars($application['priority_color'] ?? '#000') ?>">
                <?= htmlspecialchars($application['priority_name'] ?? 'Средний') ?>
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm">
            <div><strong>Статус:</strong> <?= htmlspecialchars($application['status']) ?></div>
            <div><strong>Дата создания:</strong> <?= date('d.m.Y H:i', strtotime($application['created_at'])) ?></div>
            <div><strong>Автор:</strong> <?= htmlspecialchars($application['surname'] . ' ' . $application['name'] . ' ' . $application['patronymic']) ?></div>
            <div><strong>Отдел автора:</strong> <?= htmlspecialchars($application['department_name'] ?? '—') ?></div>
            <div><strong>Категория:</strong> <?= htmlspecialchars($application['category_name'] ?? '—') ?></div>
            <div><strong>Ответственный:</strong> <?= htmlspecialchars($application['assigned_surname'] ?? '—') ?> <?= htmlspecialchars($application['assigned_name'] ?? '') ?></div>
            <div><strong>Кабинет/место:</strong> <?= htmlspecialchars($application['name_org']) ?></div>
            <?php if ($application['expected_date']): ?>
                <div><strong>Желаемая дата:</strong> <?= date('d.m.Y', strtotime($application['expected_date'])) ?></div>
            <?php endif; ?>
        </div>

        <div class="mt-4">
            <strong>Описание проблемы:</strong>
            <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-700 rounded"><?= nl2br(htmlspecialchars($application['message'])) ?></div>
        </div>

        <!-- Вложения -->
        <?php if (!empty($attachments)): ?>
            <div class="mt-4">
                <strong>Вложения:</strong>
                <ul class="list-disc pl-5 mt-2">
                    <?php foreach ($attachments as $file): ?>
                        <li>
                            <a href="/storage/uploads/<?= htmlspecialchars($file['filename']) ?>" target="_blank" class="text-blue-600 hover:underline">
                                <?= htmlspecialchars($file['original_name']) ?> (<?= round($file['size'] / 1024) ?> КБ)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <!-- Комментарии -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Комментарии</h2>
        <div class="space-y-3 mb-4">
            <?php foreach ($comments as $comment): ?>
                <div class="border-l-4 border-blue-500 pl-3 py-1">
                    <div class="text-sm text-gray-500"><?= htmlspecialchars($comment['surname'] . ' ' . $comment['name']) ?> — <?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?></div>
                    <div class="mt-1"><?= nl2br(htmlspecialchars($comment['comment'])) ?></div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($comments)): ?>
                <p class="text-gray-500">Нет комментариев.</p>
            <?php endif; ?>
        </div>

        <form method="POST" action="/application/comment">
            <?= \Core\CSRF::input() ?>
            <input type="hidden" name="application_id" value="<?= $application['application_id'] ?>">
            <textarea name="comment" rows="3" class="w-full border rounded px-3 py-2 dark:bg-gray-700" placeholder="Напишите комментарий..."></textarea>
            <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Добавить комментарий</button>
        </form>
    </div>

    <!-- История изменений (опционально) -->
    <?php if (!empty($history)): ?>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mt-6">
        <h2 class="text-xl font-semibold mb-4">История изменений</h2>
        <ul class="space-y-2 text-sm">
            <?php foreach ($history as $h): ?>
                <li><?= date('d.m.Y H:i', strtotime($h['created_at'])) ?> — 
                    <?= htmlspecialchars($h['surname'] . ' ' . $h['name']) ?>: 
                    <?= htmlspecialchars($h['field_name']) ?> 
                    (<?= htmlspecialchars($h['old_value'] ?? '—') ?> → <?= htmlspecialchars($h['new_value'] ?? '—') ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

</div>