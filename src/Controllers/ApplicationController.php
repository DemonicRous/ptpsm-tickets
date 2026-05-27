<?php
namespace Controllers;

use Core\Request;
use Core\View;
use Core\CSRF;
use Core\Validation;
use Models\Application;
use Models\Category;
use Models\Priority;

class ApplicationController {
    private $appModel;
    
    public function __construct() {
        $this->appModel = new Application();
    }
    
    public function create(Request $request) {
        $categories = (new Category())->getAll();
        $priorities = (new Priority())->getAll();
        View::render('application/create', [
            'csrf_token' => CSRF::generateToken(),
            'categories' => $categories,
            'priorities' => $priorities
        ]);
    }
    
    public function store(Request $request) {
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) {
            die('CSRF token invalid');
        }
        
        $errors = [];
        if (!Validation::required($post['name_org'] ?? '')) $errors['name_org'] = 'Укажите кабинет/место';
        if (!Validation::required($post['message'] ?? '')) $errors['message'] = 'Опишите проблему';
        
        if (!empty($errors)) {
            $categories = (new Category())->getAll();
            $priorities = (new Priority())->getAll();
            View::render('application/create', [
                'csrf_token' => CSRF::generateToken(),
                'categories' => $categories,
                'priorities' => $priorities,
                'old' => $post,
                'errors' => $errors
            ]);
            return;
        }
        
        $number = $this->appModel->generateNumber();
        $data = [
            ':user_id' => $_SESSION['user_id'],
            ':number' => $number,
            ':status' => 'Новый',
            ':category_id' => !empty($post['category_id']) ? $post['category_id'] : null,
            ':priority_id' => $post['priority_id'] ?? 2,
            ':assigned_to' => null,
            ':name_org' => $post['name_org'],
            ':message' => $post['message'],
            ':expected_date' => !empty($post['expected_date']) ? $post['expected_date'] : null
        ];
        
        $appId = $this->appModel->create($data);
        
        // Обработка вложений (если есть)
        if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
            $this->handleAttachments($appId, $_FILES['attachments']);
        }
        
        header('Location: /profile?success=created');
        exit;
    }
    
    private function handleAttachments($appId, $files) {
        $uploadDir = __DIR__ . '/../../storage/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $userId = $_SESSION['user_id'];
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                $dest = $uploadDir . $filename;
                if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                    $this->appModel->addAttachment(
                        $appId,
                        $userId,
                        $filename,
                        $files['name'][$i],
                        $files['size'][$i],
                        $files['type'][$i]
                    );
                }
            }
        }
    }
    
    public function addComment(Request $request) {
        // отдельный метод для AJAX или обычный POST
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) die('CSRF');
        $appId = $post['application_id'];
        $comment = trim($post['comment']);
        if ($comment) {
            $this->appModel->addComment($appId, $_SESSION['user_id'], $comment);
        }
        header("Location: /admin?view=application&id=$appId");
        exit;
    }
}