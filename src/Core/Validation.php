<?php
namespace Core;

class Validation {
    public static function required($value) {
        return !empty(trim($value));
    }
    
    public static function minLength($value, $min) {
        return strlen($value) >= $min;
    }
    
    public static function email($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function phone($value) {
        // Убираем всё, кроме цифр
        $digits = preg_replace('/\D/', '', $value);
        // Должно быть от 10 до 15 цифр
        return preg_match('/^[0-9]{10,15}$/', $digits);
    }
    
    public static function match($value1, $value2) {
        return $value1 === $value2;
    }
    
    public static function uniqueLogin($login, $excludeId = null) {
        $db = \Core\Database::getInstance()->getConnection();
        $sql = "SELECT COUNT(*) FROM user WHERE login = :login";
        if ($excludeId) {
            $sql .= " AND user_id != :id";
        }
        $stmt = $db->prepare($sql);
        $params = [':login' => $login];
        if ($excludeId) $params[':id'] = $excludeId;
        $stmt->execute($params);
        return $stmt->fetchColumn() == 0;
    }
}