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
    <title>Каталог велосипедов | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo SITE_NAME; ?></h1>
            <nav>
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="catalog.php">Каталог</a></li>
                    <li><a href="search.php">Подбор</a></li>
                    <li><a href="admin/login.php">Админка</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2>Каталог велосипедов</h2>
        
        <div class="filters">
            <form method="GET" action="catalog.php">
                <select name="type">
                    <option value="">Все типы</option>
                    <option value="Горный">Горный</option>
                    <option value="Шоссейный">Шоссейный</option>
                    <option value="Городской">Городской</option>
                </select>
                
                <select name="brand">
                    <option value="">Все бренды</option>
                    <?php
                    $brands = $db->query("SELECT DISTINCT brand FROM bikes ORDER BY brand");
                    while ($brand = $brands->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="'.$brand['brand'].'">'.$brand['brand'].'</option>';
                    }
                    ?>
                </select>
                
                <input type="submit" value="Применить фильтры" class="btn">
            </form>
        </div>
        
        <div class="bike-grid">
            <?php
            $sql = "SELECT * FROM bikes";
            $params = [];
            
            if (isset($_GET['type']) && !empty($_GET['type'])) {
                $sql .= " WHERE type = :type";
                $params[':type'] = $_GET['type'];
            }
            
            if (isset($_GET['brand']) && !empty($_GET['brand'])) {
                $sql .= (strpos($sql, 'WHERE') !== false) ? " AND" : " WHERE";
                $sql .= " brand = :brand";
                $params[':brand'] = $_GET['brand'];
            }
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="bike-card">
                    <img src="assets/images/'.$row['image'].'" alt="'.$row['name'].'">
                    <h3>'.$row['name'].'</h3>
                    <p>'.$row['brand'].' • '.$row['type'].'</p>
                    <p class="price">'.number_format($row['price'], 0, '', ' ').' ₽</p>
                    <a href="bike_details.php?id='.$row['id'].'" class="btn">Подробнее</a>
                </div>';
            }
            
            if ($stmt->rowCount() == 0) {
                echo '<p>По вашему запросу ничего не найдено.</p>';
            }
            ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y').' '.SITE_NAME; ?></p>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>