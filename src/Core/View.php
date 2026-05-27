<?php
namespace Core;

class View {
    public static function render($view, $data = []) {
        extract($data);
        ob_start();
        require __DIR__ . "/../Views/{$view}.php";
        $content = ob_get_clean();
        require __DIR__ . "/../Views/layouts/main.php";
    }
    
    public static function renderPartial($view, $data = []) {
        extract($data);
        require __DIR__ . "/../Views/{$view}.php";
    }
}