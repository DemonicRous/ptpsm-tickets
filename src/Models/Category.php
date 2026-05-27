<?php
namespace Models;

use Core\Database;

class Category {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name");
        return $stmt->fetchAll();
    }
    
    public function getTree() {
        // для иерархических категорий
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY parent_id, name");
        $categories = $stmt->fetchAll();
        return $this->buildTree($categories);
    }
    
    private function buildTree($elements, $parentId = 0) {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['category_id']);
                if ($children) $element['children'] = $children;
                $branch[] = $element;
            }
        }
        return $branch;
    }
}