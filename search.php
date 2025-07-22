<?php require_once 'includes/config.php'; ?>
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
    <title>Подбор велосипеда | <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            <div class="selector">
                <h2 class="selector__title">Подбор велосипеда</h2>
                <p class="selector__subtitle">Ответьте на 3 вопроса и мы подберём идеальный вариант</p>
                
                <form id="bike-selector" class="selector-form" method="POST" action="result.php">
                    <!-- Шаг 1: Тип велосипеда -->
                    <div class="selector-step active" data-step="1">
                        <h3 class="step-title">1. Выберите тип велосипеда</h3>
                        <div class="options-grid">
                            <label class="option-card">
                                <input type="radio" name="bike_type" value="Горный" class="option-input" required>
                                <div class="option-content">
                                    <img src="assets/images/mountain.png" alt="Горный" class="option-img">
                                    <span class="option-name">Горный</span>
                                </div>
                            </label>
                            
                            <label class="option-card">
                                <input type="radio" name="bike_type" value="Шоссейный" class="option-input">
                                <div class="option-content">
                                    <img src="assets/images/road.png" alt="Шоссейный" class="option-img">
                                    <span class="option-name">Шоссейный</span>
                                </div>
                            </label>
                            
                            <label class="option-card">
                                <input type="radio" name="bike_type" value="Городской" class="option-input">
                                <div class="option-content">
                                    <img src="assets/images/city.png" alt="Городской" class="option-img">
                                    <span class="option-name">Городской</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Шаг 2: Рост пользователя -->
                    <div class="selector-step" data-step="2">
                        <h3 class="step-title">2. Укажите ваш рост (см)</h3>
                        <div class="range-slider">
                            <input type="range" name="height" min="140" max="210" value="175" class="slider" id="height-slider">
                            <div class="slider-values">
                                <span>140</span>
                                <span id="slider-current-value">175</span>
                                <span>210</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Шаг 3: Дополнительные параметры -->
                    <div class="selector-step" data-step="3">
                        <h3 class="step-title">3. Дополнительные параметры</h3>
                        <div class="checkboxes">
                            <label class="checkbox-label">
                                <input type="checkbox" name="electric" class="checkbox-input">
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-text">Электрический</span>
                            </label>
                            
                            <label class="checkbox-label">
                                <input type="checkbox" name="folding" class="checkbox-input">
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-text">Складной</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-navigation">
                        <button type="button" class="btn btn-prev" disabled>Назад</button>
                        <button type="button" class="btn btn-next">Далее</button>
                        <button type="submit" class="btn btn-submit">Подобрать</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Все права защищены.</p>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>