<?php
namespace Controllers;

use Core\View;
use Models\Application;
use Models\User;

class ProfileController {
    public function index() {
        $userId = $_SESSION['user_id'];
        $appModel = new Application();
        $userModel = new User();
        $user = $userModel->findById($userId);
        $applications = $appModel->getUserApplications($userId);
        
        View::render('profile/index', [
            'user' => $user,
            'applications' => $applications
        ]);
    }
}