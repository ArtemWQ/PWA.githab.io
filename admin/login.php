<?php
session_start();
require_once '../includes/db_connect.php';

if (isset($_SESSION['user_id'])) {
    header('Location: add_bike.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $stmt = $db->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: add_bike.php');
        exit;
    } else {
        $error = "Неверное имя пользователя или пароль";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Подбор велосипеда</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- PWA мета-теги -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3498db">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="BikeSelector">
    <link rel="apple-touch-icon" href="assets/icons/icon-152x152.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админку | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 500px; margin-top: 100px;">
        <h2>Вход в админ-панель</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert" style="color: red; margin-bottom: 20px;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Имя пользователя:</label>
                <input type="text" name="username" required style="width: 100%; padding: 10px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Пароль:</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px;">
            </div>
            
            <button type="submit" class="btn">Войти</button>
        </form>
    </div>
</body>
</html>