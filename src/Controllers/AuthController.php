<?php
namespace Controllers;

use Core\Request;
use Core\View;
use Core\Validation;
use Core\CSRF;
use Models\User;

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function showLogin(Request $request) {
        if (isset($_SESSION['user_id'])) {
            header('Location: /profile');
            exit;
        }
        View::render('auth/login', ['csrf_token' => CSRF::generateToken()]);
    }
    
    public function login(Request $request) {
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) {
            die('CSRF token invalid');
        }
        $login = trim($post['login'] ?? '');
        $password = $post['password'] ?? '';
        
        if (empty($login) || empty($password)) {
            header('Location: /auth?error=empty');
            exit;
        }
        
        $user = $this->userModel->findByLogin($login);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['role'] = $user['role'];
            header('Location: /profile');
        } else {
            header('Location: /auth?error=invalid');
        }
        exit;
    }
    
    public function showRegister(Request $request) {
        if (isset($_SESSION['user_id'])) {
            header('Location: /profile');
            exit;
        }
        // Передаём списки отделов, но для регистрации не обязательно
        $departments = (new \Models\Department())->getAll();
        View::render('auth/register', [
            'csrf_token' => CSRF::generateToken(),
            'departments' => $departments
        ]);
    }
    
    public function register(Request $request) {
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) {
            die('CSRF token invalid');
        }
        
        $errors = [];
        // Валидация
        if (!Validation::required($post['surname'] ?? '')) $errors['surname'] = 'Фамилия обязательна';
        if (!Validation::required($post['name'] ?? '')) $errors['name'] = 'Имя обязательно';
        if (!Validation::required($post['patronymic'] ?? '')) $errors['patronymic'] = 'Отчество обязательно';
        if (!Validation::required($post['login'] ?? '')) $errors['login'] = 'Логин обязателен';
        elseif (!Validation::uniqueLogin($post['login'])) $errors['login'] = 'Логин уже занят';
        if (!Validation::email($post['email'] ?? '')) $errors['email'] = 'Некорректный email';
        if (!Validation::phone($post['phone'] ?? '')) $errors['phone'] = 'Некорректный телефон';
        if (!Validation::minLength($post['password'] ?? '', 6)) $errors['password'] = 'Пароль должен быть не менее 6 символов';
        if (!Validation::match($post['password'] ?? '', $post['password_repeat'] ?? '')) $errors['password_repeat'] = 'Пароли не совпадают';
        
        if (!empty($errors)) {
            // Вернуть на регистрацию с ошибками
            $departments = (new \Models\Department())->getAll();
            View::render('auth/register', [
                'csrf_token' => CSRF::generateToken(),
                'departments' => $departments,
                'old' => $post,
                'errors' => $errors
            ]);
            return;
        }
        
        $hashed = password_hash($post['password'], PASSWORD_BCRYPT);
        $userId = $this->userModel->create([
            ':surname' => $post['surname'],
            ':name' => $post['name'],
            ':patronymic' => $post['patronymic'],
            ':login' => $post['login'],
            ':email' => $post['email'],
            ':phone' => $post['phone'],
            ':password' => $hashed,
            ':role' => 'Пользователь',
            ':department_id' => !empty($post['department_id']) ? $post['department_id'] : null,
            ':position' => $post['position'] ?? null
        ]);
        
        $_SESSION['user_id'] = $userId;
        $_SESSION['login'] = $post['login'];
        $_SESSION['role'] = 'Пользователь';
        header('Location: /profile');
        exit;
    }
    
    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}