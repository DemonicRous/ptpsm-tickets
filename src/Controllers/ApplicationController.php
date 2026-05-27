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
        $categories = (new Category())->getAllWithLevel();
        $priorities = (new Priority())->getAll();
        View::render('application/create', [
            'csrf_token' => CSRF::generateToken(),
            'categories' => $categories,
            'priorities' => $priorities,
            'old' => [],
            'errors' => []
        ]);
    }

    public function addComment(Request $request) {
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) die('CSRF');
        $appId = $post['application_id'];
        $comment = trim($post['comment']);
        if ($comment) {
            $this->appModel->addComment($appId, $_SESSION['user_id'], $comment);
        }
        if ($_SESSION['role'] === 'Администратор') {
            header("Location: /admin/view?id=$appId");
        } else {
            header("Location: /profile");
        }
        exit;
    }
    
    public function store(Request $request) {
        $post = $request->post();
        if (!CSRF::verifyToken($post['csrf_token'] ?? '')) {
            die('CSRF token invalid');
        }
        
        $errors = [];
        if (!Validation::required($post['name_org'] ?? '')) $errors['name_org'] = 'Укажите кабинет/место';
        if (!Validation::required($post['message'] ?? '')) $errors['message'] = 'Опишите проблему';
        
        // Валидация даты
        $expected_date = null;
        if (!empty($post['expected_date'])) {
            $dateStr = trim($post['expected_date']);
            $date = null;
            
            // Формат YYYY-MM-DD (HTML5 date input)
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
                $date = \DateTime::createFromFormat('Y-m-d', $dateStr);
            }
            // Формат DD.MM.YYYY (ручной ввод)
            elseif (preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $dateStr)) {
                $date = \DateTime::createFromFormat('d.m.Y', $dateStr);
            }
            
            if ($date && ($date->format('Y-m-d') === $dateStr || $date->format('d.m.Y') === $dateStr)) {
                $year = (int)$date->format('Y');
                if ($year < 2000 || $year > 2100) {
                    $errors['expected_date'] = 'Год должен быть между 2000 и 2100';
                } else {
                    $expected_date = $date->format('Y-m-d');
                }
            } else {
                $errors['expected_date'] = 'Неверный формат даты. Используйте ГГГГ-ММ-ДД или ДД.ММ.ГГГГ (например, 2025-12-31 или 31.12.2025)';
            }
        }
        
        if (!empty($errors)) {
            $categories = (new Category())->getAllWithLevel();
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
            ':expected_date' => $expected_date
        ];
        
        $appId = $this->appModel->create($data);
        
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

    public function view(Request $request) {
        $id = $request->get('id');
        $app = $this->appModel->findById($id);
        if (!$app) {
            http_response_code(404);
            echo "Заявка не найдена";
            exit;
        }
        // Проверка прав: только автор или админ
        if ($_SESSION['role'] !== 'Администратор' && $app['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo "Доступ запрещён";
            exit;
        }
        $comments = $this->appModel->getComments($id);
        $attachments = $this->appModel->getAttachments($id);
        $history = $this->appModel->getHistory($id);
        
        View::render('application/view', [
            'application' => $app,
            'comments' => $comments,
            'attachments' => $attachments,
            'history' => $history,
            'csrf_token' => CSRF::generateToken()
        ]);
    }
}