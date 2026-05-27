<?php
namespace Models;

use Core\Database;

class Department {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM departments ORDER BY name");
        return $stmt->fetchAll();
    }
}