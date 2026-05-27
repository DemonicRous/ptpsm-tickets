<?php
namespace Core;

class Request
{
    public static function uri(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function get($key = null)
    {
        if ($key === null) return $_GET;
        return $_GET[$key] ?? null;
    }

    public function post($key = null)
    {
        if ($key === null) return $_POST;
        return $_POST[$key] ?? null;
    }

    public function all(): array
    {
        return array_merge($_GET, $_POST);
    }
}