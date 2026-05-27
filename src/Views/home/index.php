<?php

?>

<div class="text-center">
    <h1 class="text-4xl font-bold mb-4">Добро пожаловать в ПТПСМ: Заявки</h1>
    <p class="text-lg mb-8">Внутренняя система учёта заявок на техническую поддержку</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="space-x-4">
            <a href="/auth" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Вход</a>
            <a href="/register" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">Регистрация</a>
        </div>
    <?php else: ?>
        <a href="/profile" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Личный кабинет</a>
    <?php endif; ?>
</div>