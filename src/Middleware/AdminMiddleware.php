<?php
namespace Middleware;

class AdminMiddleware
{
    public function handle(): void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Администратор') {
            header('Location: /profile');
            exit;
        }
    }
}