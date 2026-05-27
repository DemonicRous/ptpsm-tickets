<?php
namespace Models;

use Core\Database;

class Application {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO application (user_id, number, status, category_id, priority_id, assigned_to, name_org, message, expected_date) 
                VALUES (:user_id, :number, :status, :category_id, :priority_id, :assigned_to, :name_org, :message, :expected_date)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        $appId = $this->db->lastInsertId();
        // Добавить историю: создана
        $this->addHistory($appId, $data['user_id'], 'status', null, 'Новый');
        return $appId;
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT a.*, 
                                    u.surname, u.name, u.patronymic, u.email, u.phone,
                                    cat.name as category_name, pri.name as priority_name, pri.color as priority_color,
                                    assigned.surname as assigned_surname, assigned.name as assigned_name, assigned.patronymic as assigned_patronymic,
                                    d.name as department_name
                                    FROM application a
                                    LEFT JOIN user u ON a.user_id = u.user_id
                                    LEFT JOIN categories cat ON a.category_id = cat.category_id
                                    LEFT JOIN priorities pri ON a.priority_id = pri.priority_id
                                    LEFT JOIN user assigned ON a.assigned_to = assigned.user_id
                                    LEFT JOIN departments d ON u.department_id = d.department_id
                                    WHERE a.application_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getUserApplications($userId, $limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT a.*, 
                cat.name as category_name, 
                pri.name as priority_name, 
                pri.color,
                assigned.surname as assigned_surname,
                assigned.name as assigned_name
            FROM application a
            LEFT JOIN categories cat ON a.category_id = cat.category_id
            LEFT JOIN priorities pri ON a.priority_id = pri.priority_id
            LEFT JOIN user assigned ON a.assigned_to = assigned.user_id
            WHERE a.user_id = :user_id
            ORDER BY a.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getAllApplications($filters = [], $limit = 20, $offset = 0) {
        $sql = "SELECT a.*, 
                u.surname, u.name, u.patronymic,
                cat.name as category_name, pri.name as priority_name, pri.color,
                assigned.surname as assigned_surname, assigned.name as assigned_name
                FROM application a
                LEFT JOIN user u ON a.user_id = u.user_id
                LEFT JOIN categories cat ON a.category_id = cat.category_id
                LEFT JOIN priorities pri ON a.priority_id = pri.priority_id
                LEFT JOIN user assigned ON a.assigned_to = assigned.user_id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND a.status = :status";
            $params[':status'] = $filters['status'];
        }
        if (!empty($filters['priority_id'])) {
            $sql .= " AND a.priority_id = :priority_id";
            $params[':priority_id'] = $filters['priority_id'];
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND a.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        if (!empty($filters['assigned_to'])) {
            $sql .= " AND a.assigned_to = :assigned_to";
            $params[':assigned_to'] = $filters['assigned_to'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (a.number LIKE :search OR a.message LIKE :search OR u.surname LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY a.created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function updateStatus($appId, $userId, $newStatus) {
        $old = $this->findById($appId);
        if (!$old) return false;
        $stmt = $this->db->prepare("UPDATE application SET status = :status WHERE application_id = :id");
        $stmt->execute([':status' => $newStatus, ':id' => $appId]);
        $this->addHistory($appId, $userId, 'status', $old['status'], $newStatus);
        return true;
    }
    
    public function assignTo($appId, $userId, $assignedToId) {
        $old = $this->findById($appId);
        $stmt = $this->db->prepare("UPDATE application SET assigned_to = :assigned_to WHERE application_id = :id");
        $stmt->execute([':assigned_to' => $assignedToId, ':id' => $appId]);
        $oldName = $old['assigned_surname'] . ' ' . $old['assigned_name'];
        $new = $this->findById($appId);
        $newName = $new['assigned_surname'] . ' ' . $new['assigned_name'];
        $this->addHistory($appId, $userId, 'assigned_to', $oldName, $newName);
        return true;
    }
    
    public function addHistory($appId, $userId, $field, $oldValue, $newValue) {
        $stmt = $this->db->prepare("INSERT INTO application_history (application_id, user_id, field_name, old_value, new_value) 
                                    VALUES (:app_id, :user_id, :field, :old, :new)");
        $stmt->execute([
            ':app_id' => $appId,
            ':user_id' => $userId,
            ':field' => $field,
            ':old' => $oldValue,
            ':new' => $newValue
        ]);
    }
    
    public function addComment($appId, $userId, $comment) {
        $stmt = $this->db->prepare("INSERT INTO application_comments (application_id, user_id, comment) VALUES (:app_id, :user_id, :comment)");
        $stmt->execute([':app_id' => $appId, ':user_id' => $userId, ':comment' => $comment]);
        return $this->db->lastInsertId();
    }
    
    public function getComments($appId) {
        $stmt = $this->db->prepare("SELECT c.*, u.surname, u.name, u.patronymic 
                                    FROM application_comments c
                                    JOIN user u ON c.user_id = u.user_id
                                    WHERE c.application_id = :app_id
                                    ORDER BY c.created_at ASC");
        $stmt->execute([':app_id' => $appId]);
        return $stmt->fetchAll();
    }
    
    public function addAttachment($appId, $userId, $filename, $originalName, $size, $mime) {
        $stmt = $this->db->prepare("INSERT INTO attachments (application_id, user_id, filename, original_name, size, mime) 
                                    VALUES (:app_id, :user_id, :filename, :original_name, :size, :mime)");
        $stmt->execute([
            ':app_id' => $appId,
            ':user_id' => $userId,
            ':filename' => $filename,
            ':original_name' => $originalName,
            ':size' => $size,
            ':mime' => $mime
        ]);
    }
    
    public function getAttachments($appId) {
        $stmt = $this->db->prepare("SELECT * FROM attachments WHERE application_id = :app_id ORDER BY created_at");
        $stmt->execute([':app_id' => $appId]);
        return $stmt->fetchAll();
    }
    
    public function generateNumber() {
        $date = date('Ymd');
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM application WHERE number LIKE :prefix");
        $prefix = "APP-$date-%";
        $stmt->execute([':prefix' => $prefix]);
        $count = $stmt->fetchColumn() + 1;
        return sprintf("APP-%s-%03d", $date, $count);
    }
    
    public function getStatistics() {
        // Для админ-панели
        $stats = [];
        $stmt = $this->db->query("SELECT status, COUNT(*) as count FROM application GROUP BY status");
        $stats['by_status'] = $stmt->fetchAll();
        
        $stmt = $this->db->query("SELECT pri.name, COUNT(*) as count FROM application a LEFT JOIN priorities pri ON a.priority_id = pri.priority_id GROUP BY a.priority_id");
        $stats['by_priority'] = $stmt->fetchAll();
        
        $stmt = $this->db->query("SELECT DATE(created_at) as day, COUNT(*) as count FROM application GROUP BY DATE(created_at) ORDER BY day DESC LIMIT 7");
        $stats['last_7_days'] = $stmt->fetchAll();
        
        return $stats;
    }

    public function getHistory($appId) {
        $stmt = $this->db->prepare("
            SELECT h.*, u.surname, u.name 
            FROM application_history h
            JOIN user u ON h.user_id = u.user_id
            WHERE h.application_id = :app_id
            ORDER BY h.created_at ASC
        ");
        $stmt->execute([':app_id' => $appId]);
        return $stmt->fetchAll();
    }
}