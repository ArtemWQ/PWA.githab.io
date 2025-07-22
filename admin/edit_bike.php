<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/config.php';

session_start();

if (!isAdmin()) {
    redirect('login.php');
}

$errors = [];
$success = '';

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$bikeId = (int)$_GET['id'];

// Получаем данные велосипеда
$stmt = $db->prepare("SELECT * FROM bikes WHERE id = ?");
$stmt->execute([$bikeId]);
$bike = $stmt->fetch();

if (!$bike) {
    $_SESSION['error'] = 'Велосипед не найден';
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name']),
        'type' => $_POST['type'],
        'brand' => trim($_POST['brand']),
        'price' => (float)$_POST['price'],
        'wheel_size' => (int)$_POST['wheel_size'],
        'frame_size' => trim($_POST['frame_size']),
        'frame_material' => trim($_POST['frame_material']),
        'is_electric' => isset($_POST['is_electric']) ? 1 : 0,
        'is_folding' => isset($_POST['is_folding']) ? 1 : 0,
        'gears' => (int)$_POST['gears'],
        'weight' => (float)$_POST['weight'],
        'description' => trim($_POST['description']),
        'image' => $bike['image']
    ];
    
    $errors = validateBikeData($data);
    
    if (empty($errors)) {
        // Обработка загрузки нового изображения
        if (!empty($_FILES['image']['name'])) {
            $newImage = uploadImage($_FILES['image']);
            if ($newImage) {
                // Удаляем старое изображение
                if (!empty($bike['image'])) {
                    $oldImagePath = '../assets/images/' . $bike['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $data['image'] = $newImage;
            }
        }
        
        // Обновляем данные в БД
        $stmt = $db->prepare("UPDATE bikes SET 
                            name = ?, type = ?, brand = ?, price = ?, wheel_size = ?, 
                            frame_size = ?, frame_material = ?, is_electric = ?, 
                            is_folding = ?, gears = ?, weight = ?, description = ?, image = ?
                            WHERE id = ?");
        
        $stmt->execute([
            $data['name'], $data['type'], $data['brand'], $data['price'],
            $data['wheel_size'], $data['frame_size'], $data['frame_material'],
            $data['is_electric'], $data['is_folding'], $data['gears'],
            $data['weight'], $data['description'], $data['image'], $bikeId
        ]);
        
        $success = 'Данные велосипеда успешно обновлены';
        $bike = $data; // Обновляем данные для отображения
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
    <title>Редактирование велосипеда | <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header__inner">
                <h1 class="header__logo"><?= SITE_NAME ?> - Админка</h1>
                <nav class="nav">
                    <a href="add_bike.php">Добавить</a>
                    <a href="edit_bike.php">Редактировать</a>
                    <a href="delete_bike.php">Удалить</a>
                    <a href="logout.php">Выйти</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <h2>Редактирование велосипеда</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="bike-form">
                <div class="form-group">
                    <label>Название:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($bike['name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Тип:</label>
                    <select name="type" required>
                        <?php foreach (getBikeTypes() as $type): ?>
                            <option value="<?= $type ?>" <?= $type === $bike['type'] ? 'selected' : '' ?>><?= $type ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Бренд:</label>
                    <input type="text" name="brand" value="<?= htmlspecialchars($bike['brand']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Цена (₽):</label>
                    <input type="number" step="0.01" name="price" value="<?= $bike['price'] ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Размер колес (дюймы):</label>
                        <input type="number" name="wheel_size" value="<?= $bike['wheel_size'] ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Рост (см):</label>
                        <input type="text" name="frame_size" value="<?= htmlspecialchars($bike['frame_size']) ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Материал рамы:</label>
                    <input type="text" name="frame_material" value="<?= htmlspecialchars($bike['frame_material']) ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_electric" <?= $bike['is_electric'] ? 'checked' : '' ?>>
                            Электрический
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_folding" <?= $bike['is_folding'] ? 'checked' : '' ?>>
                            Складной
                        </label>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Количество скоростей:</label>
                        <input type="number" name="gears" value="<?= $bike['gears'] ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Вес (кг):</label>
                        <input type="number" step="0.1" name="weight" value="<?= $bike['weight'] ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Описание:</label>
                    <textarea name="description" required><?= htmlspecialchars($bike['description']) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Изображение:</label>
                    <?php if (!empty($bike['image'])): ?>
                        <img src="../assets/images/<?= $bike['image'] ?>" alt="Текущее изображение" style="max-width: 200px; display: block; margin-bottom: 10px;">
                    <?php endif; ?>
                    <input type="file" name="image">
                </div>
                
                <button type="submit" class="btn">Сохранить изменения</button>
                <a href="index.php" class="btn btn-cancel">Отмена</a>
            </form>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>