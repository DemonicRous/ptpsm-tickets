<?php
namespace Controllers;

use Core\CSRF;
use Core\View;
use Core\Request;
use Core\Validation;
use Models\Application;
use Models\User;
use Models\Category;
use Models\Priority;
use Models\Department;

class AdminController {
    private $appModel;
    private $userModel;
    private $categoryModel;
    private $priorityModel;
    private $departmentModel;
    
    public function __construct() {
        $this->appModel = new Application();
        $this->userModel = new User();
        $this->categoryModel = new Category();
        $this->priorityModel = new Priority();
        $this->departmentModel = new Department();
    }
    
    // ========== ОСНОВНАЯ АДМИН-ПАНЕЛЬ ==========
    public function index(Request $request) {
        $filters = [
            'status' => $request->get('status'),
            'priority_id' => $request->get('priority'),
            'category_id' => $request->get('category'),
            'assigned_to' => $request->get('assigned'),
            'search' => $request->get('search')
        ];
        $applications = $this->appModel->getAllApplications($filters);
        $users = $this->userModel->getAllUsers();
        $categories = $this->categoryModel->getAll();
        $priorities = $this->priorityModel->getAll();
        $stats = $this->appModel->getStatistics();
        
        View::render('admin/index', [
            'applications' => $applications,
            'users' => $users,
            'categories' => $categories,
            'priorities' => $priorities,
            'filters' => $filters,
            'stats' => $stats
        ]);
    }
    
    public function updateStatus(Request $request) {
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) die('CSRF');
        $appId = $post['application_id'];
        $newStatus = $post['status'];
        $this->appModel->updateStatus($appId, $_SESSION['user_id'], $newStatus);
        header('Location: /admin');
        exit;
    }
    
    public function assign(Request $request) {
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) die('CSRF');
        $appId = $post['application_id'];
        $assignTo = $post['assigned_to'];
        $this->appModel->assignTo($appId, $_SESSION['user_id'], $assignTo);
        header('Location: /admin');
        exit;
    }
    
    public function viewApplication(Request $request) {
        $id = $request->get('id');
        $app = $this->appModel->findById($id);
        if (!$app) {
            http_response_code(404);
            echo "Заявка не найдена";
            exit;
        }
        $comments = $this->appModel->getComments($id);
        $attachments = $this->appModel->getAttachments($id);
        $history = $this->appModel->getHistory($id);
        $users = $this->userModel->getAllUsers();
        
        View::render('admin/view', [
            'application' => $app,
            'comments' => $comments,
            'attachments' => $attachments,
            'history' => $history,
            'users' => $users,
            'csrf_token' => CSRF::generateToken()
        ]);
    }

    // ========== УПРАВЛЕНИЕ ПОЛЬЗОВАТЕЛЯМИ ==========

    public function users(Request $request) {
        $users = $this->userModel->getAllUsers();
        View::render('admin/users/index', [
            'users' => $users,
            'csrf_token' => CSRF::generateToken()
        ]);
    }

    public function createUser(Request $request) {
        $departments = $this->departmentModel->getAll();
        View::render('admin/users/create', [
            'csrf_token' => CSRF::generateToken(),
            'departments' => $departments,
            'old' => [],
            'errors' => []
        ]);
    }

    public function storeUser(Request $request) {
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) die('CSRF token invalid');

        $errors = [];
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
            $departments = $this->departmentModel->getAll();
            View::render('admin/users/create', [
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
            ':role' => $post['role'] ?? 'Пользователь',
            ':department_id' => !empty($post['department_id']) ? $post['department_id'] : null,
            ':position' => $post['position'] ?? null
        ]);

        header('Location: /admin/users?success=created');
        exit;
    }

    public function editUser(Request $request) {
        $id = $request->get('id');
        $user = $this->userModel->findById($id);
        if (!$user) {
            http_response_code(404);
            echo "Пользователь не найден";
            exit;
        }
        $departments = $this->departmentModel->getAll();
        View::render('admin/users/edit', [
            'csrf_token' => CSRF::generateToken(),
            'user' => $user,
            'departments' => $departments,
            'old' => [],
            'errors' => []
        ]);
    }

    public function updateUser(Request $request) {
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) die('CSRF token invalid');

        $id = $post['user_id'];
        $user = $this->userModel->findById($id);
        if (!$user) {
            header('Location: /admin/users?error=not_found');
            exit;
        }

        $errors = [];
        if (!Validation::required($post['surname'] ?? '')) $errors['surname'] = 'Фамилия обязательна';
        if (!Validation::required($post['name'] ?? '')) $errors['name'] = 'Имя обязательно';
        if (!Validation::required($post['patronymic'] ?? '')) $errors['patronymic'] = 'Отчество обязательно';
        if (!Validation::required($post['login'] ?? '')) $errors['login'] = 'Логин обязателен';
        elseif (!Validation::uniqueLogin($post['login'], $id)) $errors['login'] = 'Логин уже занят другим пользователем';
        if (!Validation::email($post['email'] ?? '')) $errors['email'] = 'Некорректный email';
        if (!Validation::phone($post['phone'] ?? '')) $errors['phone'] = 'Некорректный телефон';

        $password = null;
        if (!empty($post['password'])) {
            if (!Validation::minLength($post['password'], 6)) $errors['password'] = 'Пароль должен быть не менее 6 символов';
            elseif (!Validation::match($post['password'], $post['password_repeat'] ?? '')) $errors['password_repeat'] = 'Пароли не совпадают';
            else $password = password_hash($post['password'], PASSWORD_BCRYPT);
        }

        if (!empty($errors)) {
            $departments = $this->departmentModel->getAll();
            View::render('admin/users/edit', [
                'csrf_token' => CSRF::generateToken(),
                'user' => array_merge($user, $post),
                'departments' => $departments,
                'old' => $post,
                'errors' => $errors
            ]);
            return;
        }

        $this->userModel->update($id, [
            ':surname' => $post['surname'],
            ':name' => $post['name'],
            ':patronymic' => $post['patronymic'],
            ':login' => $post['login'],
            ':email' => $post['email'],
            ':phone' => $post['phone'],
            ':role' => $post['role'] ?? 'Пользователь',
            ':department_id' => !empty($post['department_id']) ? $post['department_id'] : null,
            ':position' => $post['position'] ?? null,
            ':password' => $password
        ]);

        header('Location: /admin/users?success=updated');
        exit;
    }

    public function deleteUser(Request $request) {
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) die('CSRF token invalid');

        $id = $post['user_id'];
        if ($id == $_SESSION['user_id']) {
            header('Location: /admin/users?error=self_delete');
            exit;
        }

        $this->userModel->delete($id);
        header('Location: /admin/users?success=deleted');
        exit;
    }
}