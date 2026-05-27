<?php
namespace Models;

use Core\Database;

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function findByLogin($login) {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE login = :login");
        $stmt->execute([':login' => $login]);
        return $stmt->fetch();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT u.*, d.name as department_name 
            FROM user u 
            LEFT JOIN departments d ON u.department_id = d.department_id 
            WHERE u.user_id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $sql = "INSERT INTO user (surname, name, patronymic, login, email, phone, password, role, department_id, position) 
                VALUES (:surname, :name, :patronymic, :login, :email, :phone, :password, :role, :department_id, :position)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }
    
    public function getAllUsers() {
        $stmt = $this->db->query("SELECT u.*, d.name as department_name FROM user u LEFT JOIN departments d ON u.department_id = d.department_id ORDER BY u.user_id");
        return $stmt->fetchAll();
    }
    
    public function getTechnicians() {
        // пользователи, которые могут быть назначены ответственными (например, все, кроме обычных пользователей? в текущей модели только роль, но можно добавить флаг is_tech)
        // упростим: вернём всех пользователей с ролью "Администратор" плюс, возможно, отдельная роль "Техник". Добавим позже.
        $stmt = $this->db->prepare("SELECT * FROM user WHERE role IN ('Администратор') OR user_id IN (SELECT assigned_to FROM application WHERE assigned_to IS NOT NULL)");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        if (isset($data[':surname'])) { $fields[] = "surname = :surname"; $params[':surname'] = $data[':surname']; }
        if (isset($data[':name'])) { $fields[] = "name = :name"; $params[':name'] = $data[':name']; }
        if (isset($data[':patronymic'])) { $fields[] = "patronymic = :patronymic"; $params[':patronymic'] = $data[':patronymic']; }
        if (isset($data[':login'])) { $fields[] = "login = :login"; $params[':login'] = $data[':login']; }
        if (isset($data[':email'])) { $fields[] = "email = :email"; $params[':email'] = $data[':email']; }
        if (isset($data[':phone'])) { $fields[] = "phone = :phone"; $params[':phone'] = $data[':phone']; }
        if (isset($data[':role'])) { $fields[] = "role = :role"; $params[':role'] = $data[':role']; }
        if (isset($data[':department_id'])) { $fields[] = "department_id = :department_id"; $params[':department_id'] = $data[':department_id']; }
        if (isset($data[':position'])) { $fields[] = "position = :position"; $params[':position'] = $data[':position']; }
        if (isset($data[':password']) && $data[':password'] !== null) { $fields[] = "password = :password"; $params[':password'] = $data[':password']; }
        
        if (empty($fields)) return false;
        
        $sql = "UPDATE user SET " . implode(', ', $fields) . " WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM user WHERE user_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function updateProfile($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        if (isset($data['surname'])) { $fields[] = "surname = :surname"; $params[':surname'] = $data['surname']; }
        if (isset($data['name'])) { $fields[] = "name = :name"; $params[':name'] = $data['name']; }
        if (isset($data['patronymic'])) { $fields[] = "patronymic = :patronymic"; $params[':patronymic'] = $data['patronymic']; }
        if (isset($data['email'])) { $fields[] = "email = :email"; $params[':email'] = $data['email']; }
        if (isset($data['phone'])) { $fields[] = "phone = :phone"; $params[':phone'] = $data['phone']; }
        if (isset($data['password']) && !empty($data['password'])) { 
            $fields[] = "password = :password"; 
            $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        
        if (empty($fields)) return false;
        
        $sql = "UPDATE user SET " . implode(', ', $fields) . " WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateAvatar($userId, $avatarPath) {
        $stmt = $this->db->prepare("UPDATE user SET avatar = :avatar WHERE user_id = :id");
        return $stmt->execute([':avatar' => $avatarPath, ':id' => $userId]);
    }

}