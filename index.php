<?php include 'includes/db_connect.php'; ?>
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
    <title><?php echo SITE_NAME; ?> - Подбор велосипеда</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header__inner">
                <h1 class="header__logo"><?php echo SITE_NAME; ?></h1>
                <nav class="nav">
                    <a href="index.php" class="nav__link">Главная</a>
                    <a href="catalog.php" class="nav__link">Каталог</a>
                    <a href="search.php" class="nav__link">Подбор</a>
                    <a href="admin/login.php" class="nav__link">Админка</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <section class="hero">
                <h2 class="hero__title">Найди свой идеальный велосипед</h2>
                <p class="hero__subtitle">Ответь на несколько вопросов и мы подберем тебе лучший вариант</p>
                <a href="search.php" class="btn btn-primary">Начать подбор</a>
            </section>

            <section class="popular-bikes">
                <h2 class="section-title">Популярные модели</h2>
                <div class="bike-grid">
                    <?php
                    $stmt = $db->query("SELECT * FROM bikes ORDER BY RAND() LIMIT 3");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="bike-card">
                            <img src="assets/images/'.$row['image'].'" alt="'.$row['name'].'" class="bike-card__img">
                            <div class="bike-card__info">
                                <h3 class="bike-card__name">'.$row['name'].'</h3>
                                <p class="bike-card__meta">'.$row['brand'].' • '.$row['type'].'</p>
                                <p class="bike-card__price">'.number_format($row['price'], 0, '', ' ').' ₽</p>
                                <a href="bike_details.php?id='.$row['id'].'" class="btn btn-secondary">Подробнее</a>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p class="footer__text">&copy; <?php echo date('Y').' '.SITE_NAME; ?></p>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>