<?php
namespace Controllers;

use Core\CSRF;
use Core\View;
use Core\Request;
use Models\Application;
use Models\User;
use Models\Category;
use Models\Priority;

class AdminController {
    private $appModel;
    
    public function __construct() {
        $this->appModel = new Application();
    }
    
    public function index(Request $request) {
        $filters = [
            'status' => $request->get('status'),
            'priority_id' => $request->get('priority'),
            'category_id' => $request->get('category'),
            'assigned_to' => $request->get('assigned'),
            'search' => $request->get('search')
        ];
        $applications = $this->appModel->getAllApplications($filters);
        $users = (new User())->getAllUsers();
        $categories = (new Category())->getAll();
        $priorities = (new Priority())->getAll();
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
        $users = (new \Models\User())->getAllUsers();
        
        View::render('admin/view', [
            'application' => $app,
            'comments' => $comments,
            'attachments' => $attachments,
            'history' => $history,
            'users' => $users,
            'csrf_token' => \Core\CSRF::generateToken()
        ]);
    }
}