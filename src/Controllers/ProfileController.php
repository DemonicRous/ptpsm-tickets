<?php
namespace Controllers;

use Core\View;
use Core\Request;
use Core\CSRF;
use Core\Validation;
use Models\Application;
use Models\User;

class ProfileController {
    private $userModel;
    private $appModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->appModel = new Application();
    }
    
    public function index() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        $applications = $this->appModel->getUserApplications($userId);
        
        View::render('profile/index', [
            'user' => $user,
            'applications' => $applications
        ]);
    }
    
    public function edit() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        
        View::render('profile/edit', [
            'csrf_token' => CSRF::generateToken(),
            'user' => $user,
            'old' => [],
            'errors' => []
        ]);
    }
    
    public function update(Request $request) {
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) die('CSRF token invalid');
        
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        
        $errors = [];
        if (!Validation::required($post['surname'] ?? '')) $errors['surname'] = 'Фамилия обязательна';
        if (!Validation::required($post['name'] ?? '')) $errors['name'] = 'Имя обязательно';
        if (!Validation::required($post['patronymic'] ?? '')) $errors['patronymic'] = 'Отчество обязательно';
        if (!Validation::email($post['email'] ?? '')) $errors['email'] = 'Некорректный email';
        if (!Validation::phone($post['phone'] ?? '')) $errors['phone'] = 'Некорректный телефон';
        
        if (!empty($post['password'])) {
            if (!Validation::minLength($post['password'], 6)) $errors['password'] = 'Пароль должен быть не менее 6 символов';
            elseif (!Validation::match($post['password'], $post['password_repeat'] ?? '')) $errors['password_repeat'] = 'Пароли не совпадают';
        }
        
        if (!empty($errors)) {
            View::render('profile/edit', [
                'csrf_token' => CSRF::generateToken(),
                'user' => $user,
                'old' => $post,
                'errors' => $errors
            ]);
            return;
        }
        
        $updateData = [
            'surname' => $post['surname'],
            'name' => $post['name'],
            'patronymic' => $post['patronymic'],
            'email' => $post['email'],
            'phone' => $post['phone']
        ];
        
        if (!empty($post['password'])) {
            $updateData['password'] = $post['password'];
        }
        
        $this->userModel->updateProfile($userId, $updateData);
        
        header('Location: /profile?success=updated');
        exit;
    }
    
    public function uploadAvatar(Request $request) {
        if (!CSRF::verifyToken($_POST['csrf_token'] ?? '')) die('CSRF token invalid');
        
        $userId = $_SESSION['user_id'];
        
        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            header('Location: /profile/edit?error=avatar');
            exit;
        }
        
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($_FILES['avatar']['type'], $allowed)) {
            header('Location: /profile/edit?error=avatar_type');
            exit;
        }
        
        $uploadDir = __DIR__ . '/../../storage/avatars/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
        $dest = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
            // Удаляем старый аватар, если есть
            $user = $this->userModel->findById($userId);
            if (!empty($user['avatar']) && file_exists($uploadDir . $user['avatar'])) {
                unlink($uploadDir . $user['avatar']);
            }
            $this->userModel->updateAvatar($userId, $filename);
            header('Location: /profile?success=avatar');
        } else {
            header('Location: /profile/edit?error=avatar_upload');
        }
        exit;
    }
}