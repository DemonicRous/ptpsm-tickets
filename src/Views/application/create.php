<?php
/** @var string $csrf_token */
/** @var array $categories */
/** @var array $priorities */
/** @var array $old */
/** @var array $errors */
?>

<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Новая заявка</h2>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php foreach ($errors as $field => $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/application/store" enctype="multipart/form-data">
        <?= \Core\CSRF::input() ?>

        <div class="mb-4">
            <label class="block mb-1">Кабинет / Место *</label>
            <input type="text" name="name_org" value="<?= htmlspecialchars($old['name_org'] ?? '') ?>" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Категория</label>
            <select name="category_id" class="w-full border rounded px-3 py-2 dark:bg-gray-700">
                <option value="">-- Выберите --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>" <?= (($old['category_id'] ?? '') == $cat['category_id']) ? 'selected' : '' ?>>
                        <?= str_repeat('—', $cat['level'] ?? 0) . htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Приоритет</label>
            <select name="priority_id" class="w-full border rounded px-3 py-2 dark:bg-gray-700">
                <?php foreach ($priorities as $pri): ?>
                    <option value="<?= $pri['priority_id'] ?>" <?= (($old['priority_id'] ?? 2) == $pri['priority_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($pri['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Желаемая дата выполнения</label>
            <input type="date" name="expected_date" value="<?= htmlspecialchars($old['expected_date'] ?? '') ?>" class="w-full border rounded px-3 py-2 dark:bg-gray-700">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Описание проблемы *</label>
            <textarea name="message" rows="5" class="w-full border rounded px-3 py-2 dark:bg-gray-700" required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Вложения (несколько файлов)</label>
            <input type="file" name="attachments[]" multiple class="w-full border rounded px-3 py-2 dark:bg-gray-700">
            <p class="text-xs text-gray-500">Максимальный размер каждого файла: 10 МБ</p>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Отправить заявку</button>
    </form>
</div>