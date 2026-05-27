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
        $stmt = $this->db->prepare("SELECT * FROM user WHERE user_id = :id");
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
    
    public function update($id, $data) {
        // динамическое обновление
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
}