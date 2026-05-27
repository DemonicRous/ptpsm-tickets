<?php
namespace Models;

use Core\Database;

class Priority {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM priorities ORDER BY sort_order");
        return $stmt->fetchAll();
    }
}