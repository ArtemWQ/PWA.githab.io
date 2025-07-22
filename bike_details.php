<?php
require_once 'includes/db_connect.php';

if (!isset($_GET['id']) {
    header('Location: catalog.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM bikes WHERE id = ?");
$stmt->execute([$_GET['id']]);
$bike = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bike) {
    header('Location: catalog.php');
    exit;
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
    <title><?= htmlspecialchars($bike['name']) ?> | <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header__inner">
                <h1 class="header__logo"><?= SITE_NAME ?></h1>
                <nav class="nav">
                    <a href="index.php" class="nav__link">Главная</a>
                    <a href="catalog.php" class="nav__link">Каталог</a>
                    <a href="search.php" class="nav__link">Подбор</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="bike-details">
                <div class="bike-gallery">
                    <img src="assets/images/<?= htmlspecialchars($bike['image']) ?>" alt="<?= htmlspecialchars($bike['name']) ?>" class="main-image">
                </div>
                
                <div class="bike-info">
                    <h1 class="bike-title"><?= htmlspecialchars($bike['name']) ?></h1>
                    <p class="bike-brand">Бренд: <?= htmlspecialchars($bike['brand']) ?></p>
                    
                    <div class="bike-meta">
                        <span class="bike-type">Тип: <?= htmlspecialchars($bike['type']) ?></span>
                        <span class="bike-price"><?= number_format($bike['price'], 0, '', ' ') ?> ₽</span>
                    </div>
                    
                    <div class="bike-specs">
                        <h3>Характеристики:</h3>
                        <ul>
                            <li>Размер колес: <?= $bike['wheel_size'] ?> дюймов</li>
                            <li>Рекомендуемый рост: <?= htmlspecialchars($bike['frame_size']) ?></li>
                            <li>Количество скоростей: <?= $bike['gears'] ?></li>
                            <li>Вес: <?= $bike['weight'] ?> кг</li>
                            <li>Материал рамы: <?= htmlspecialchars($bike['frame_material']) ?></li>
                            <li><?= $bike['is_electric'] ? '⚡ Электрический' : '⛽ Обычный' ?></li>
                            <li><?= $bike['is_folding'] ? '🔄 Складной' : '🔒 Не складной' ?></li>
                        </ul>
                    </div>
                    
                    <div class="bike-description">
                        <h3>Описание:</h3>
                        <p><?= nl2br(htmlspecialchars($bike['description'])) ?></p>
                    </div>
                    
                    <a href="catalog.php" class="btn">Вернуться в каталог</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>