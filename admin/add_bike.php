<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $wheel_size = $_POST['wheel_size'];
    $frame_material = $_POST['frame_material'];
    $gears = $_POST['gears'];
    $weight = $_POST['weight'];
    $description = $_POST['description'];
    
    // Обработка загрузки изображения
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);
        
        // Проверка типа файла
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $image = $_FILES['image']['name'];
            }
        }
    }
    
    try {
        $stmt = $db->prepare("INSERT INTO bikes (name, type, brand, price, wheel_size, frame_material, gears, weight, description, image) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $type, $brand, $price, $wheel_size, $frame_material, $gears, $weight, $description, $image]);
        
        $success = "Велосипед успешно добавлен!";
    } catch (PDOException $e) {
        $error = "Ошибка при добавлении велосипеда: " . $e->getMessage();
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
    <title>Добавить велосипед | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo SITE_NAME; ?> - Админка</h1>
            <nav>
                <ul>
                    <li><a href="add_bike.php">Добавить велосипед</a></li>
                    <li><a href="edit_bike.php">Редактировать</a></li>
                    <li><a href="delete_bike.php">Удалить</a></li>
                    <li><a href="logout.php">Выйти</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2>Добавить новый велосипед</h2>
        
        <?php if (isset($success)): ?>
            <div class="alert" style="color: green; margin-bottom: 20px;"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert" style="color: red; margin-bottom: 20px;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Название:</label>
                <input type="text" name="name" required style="width: 100%; padding: 10px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Тип:</label>
                <select name="type" required style="width: 100%; padding: 10px;">
                    <option value="Горный">Горный</option>
                    <option value="Шоссейный">Шоссейный</option>
                    <option value="Городской">Городской</option>
                </select>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Бренд:</label>
                <input type="text" name="brand" required style="width: 100%; padding: 10px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Цена:</label>
                <input type="number" name="price" required style="width: 100%; padding: 10px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Размер колес (дюймы):</label>
                <input type="number" name="wheel_size" required style="width: 100%; padding: 10px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Материал рамы:</label>
                <input type="text" name="frame_material" required style="width: 100%; padding: 10px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Количество скоростей:</label>
                <input type="number" name="gears" required style="width: 100%; padding: 10px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Вес (кг):</label>
                <input type="number" step="0.01" name="weight" required style="width: 100%; padding: 10px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Описание:</label>
                <textarea name="description" required style="width: 100%; padding: 10px; min-height: 100px;"></textarea>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Изображение:</label>
                <input type="file" name="image" required style="width: 100%; padding: 10px;">
            </div>
            
            <button type="submit" class="btn">Добавить велосипед</button>
        </form>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y').' '.SITE_NAME; ?></p>
        </div>
    </footer>
</body>
</html>