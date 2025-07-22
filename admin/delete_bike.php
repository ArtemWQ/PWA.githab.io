<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/config.php';

session_start();

if (!isAdmin()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$bikeId = (int)$_GET['id'];

// Проверяем существование велосипеда
$stmt = $db->prepare("SELECT image FROM bikes WHERE id = ?");
$stmt->execute([$bikeId]);
$bike = $stmt->fetch();

if (!$bike) {
    $_SESSION['error'] = 'Велосипед не найден';
    redirect('index.php');
}

// Удаляем изображение если оно есть
if (!empty($bike['image'])) {
    $imagePath = '../assets/images/' . $bike['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// Удаляем велосипед из БД
$stmt = $db->prepare("DELETE FROM bikes WHERE id = ?");
$stmt->execute([$bikeId]);

$_SESSION['success'] = 'Велосипед успешно удалён';
redirect('index.php');
?>