<?php
namespace Models;

use Core\Database;

class Notification {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($userId, $appId, $message) {
        $stmt = $this->db->prepare("INSERT INTO notifications (user_id, application_id, message) VALUES (:uid, :aid, :msg)");
        $stmt->execute([':uid' => $userId, ':aid' => $appId, ':msg' => $message]);
    }
    
    public function getUnread($userId) {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = :uid AND is_read = 0 ORDER BY created_at DESC");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }
    
    public function markAsRead($notifId) {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = :id");
        $stmt->execute([':id' => $notifId]);
    }
}