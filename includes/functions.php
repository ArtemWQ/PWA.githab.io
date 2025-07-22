<?php
function isAdmin() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function formatPrice($price) {
    return number_format($price, 0, '', ' ');
}

function getBikeTypes() {
    return ['Горный', 'Шоссейный', 'Городской'];
}

function validateBikeData($data) {
    $errors = [];
    
    if (empty($data['name'])) {
        $errors[] = 'Не указано название велосипеда';
    }
    
    if (empty($data['type']) || !in_array($data['type'], getBikeTypes())) {
        $errors[] = 'Неверный тип велосипеда';
    }
    
    if (!is_numeric($data['price']) || $data['price'] <= 0) {
        $errors[] = 'Неверная цена';
    }
    
    return $errors;
}

function uploadImage($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $validTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = mime_content_type($file['tmp_name']);
    
    if (!in_array($fileType, $validTypes)) {
        return null;
    }
    
    $uploadDir = 'assets/images/';
    $filename = uniqid() . '_' . basename($file['name']);
    $destination = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $filename;
    }
    
    return null;
}
?>