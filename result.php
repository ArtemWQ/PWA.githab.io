<?php
require_once 'includes/db_connect.php';

// Получаем параметры из URL
$bike_type = $_GET['bike_type'] ?? '';
$height = $_GET['height'] ?? 175;
$electric = isset($_GET['electric']) ? 1 : 0;
$folding = isset($_GET['folding']) ? 1 : 0;

// Подбираем велосипеды по параметрам
$sql = "SELECT * FROM bikes WHERE type = :type AND is_electric = :electric AND is_folding = :folding ORDER BY price";
$stmt = $db->prepare($sql);
$stmt->execute([
    ':type' => $bike_type,
    ':electric' => $electric,
    ':folding' => $folding
]);
$bikes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Результаты подбора | <?= SITE_NAME ?></title>
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
                    <a href="search.php" class="nav__link active">Подбор</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="results">
                <h2 class="results__title">Результаты подбора</h2>
                
                <div class="search-params">
                    <h3>Ваши параметры:</h3>
                    <ul>
                        <li>Тип: <?= htmlspecialchars($bike_type) ?></li>
                        <li>Рост: <?= (int)$height ?> см</li>
                        <li>Электрический: <?= $electric ? 'Да' : 'Нет' ?></li>
                        <li>Складной: <?= $folding ? 'Да' : 'Нет' ?></li>
                    </ul>
                </div>
                
                <?php if (count($bikes) > 0): ?>
                    <div class="bike-grid">
                        <?php foreach ($bikes as $bike): ?>
                            <div class="bike-card">
                                <img src="assets/images/<?= htmlspecialchars($bike['image']) ?>" alt="<?= htmlspecialchars($bike['name']) ?>" class="bike-img">
                                <div class="bike-info">
                                    <h3 class="bike-name"><?= htmlspecialchars($bike['name']) ?></h3>
                                    <p class="bike-brand"><?= htmlspecialchars($bike['brand']) ?></p>
                                    <p class="bike-type">Тип: <?= htmlspecialchars($bike['type']) ?></p>
                                    <p class="bike-size">Рост: <?= htmlspecialchars($bike['frame_size']) ?></p>
                                    <?php if ($bike['is_electric']): ?>
                                        <p class="bike-feature">⚡ Электрический</p>
                                    <?php endif; ?>
                                    <?php if ($bike['is_folding']): ?>
                                        <p class="bike-feature">🔄 Складной</p>
                                    <?php endif; ?>
                                    <p class="bike-price"><?= number_format($bike['price'], 0, '', ' ') ?> ₽</p>
                                    <a href="bike_details.php?id=<?= $bike['id'] ?>" class="btn">Подробнее</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <p>К сожалению, по вашим параметрам не найдено подходящих велосипедов.</p>
                        <a href="search.php" class="btn">Попробовать другие параметры</a>
                    </div>
                <?php endif; ?>
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