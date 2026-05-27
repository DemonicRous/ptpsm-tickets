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

    public function getAllWithLevel() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY parent_id, name");
        $categories = $stmt->fetchAll();
        // построим дерево и добавим level
        $tree = $this->buildTreeWithLevel($categories);
        // преобразуем обратно в плоский список с level
        $flat = [];
        $this->flattenTree($tree, $flat);
        return $flat;
    }

    private function buildTreeWithLevel($elements, $parentId = 0) {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTreeWithLevel($elements, $element['category_id']);
                $element['children'] = $children;
                $branch[] = $element;
            }
        }
        return $branch;
    }

    private function flattenTree($tree, &$flat, $level = 0) {
        foreach ($tree as $node) {
            $node['level'] = $level;
            $flat[] = $node;
            if (!empty($node['children'])) {
                $this->flattenTree($node['children'], $flat, $level + 1);
            }
        }
    }
}