<?php
namespace Middleware;

class AuthMiddleware
{
    public function handle(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth');
            exit;
        }
    }
}