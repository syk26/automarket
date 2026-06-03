<?php
require_once 'config.php';

$model = $_GET['model'] ?? '';
$year = $_GET['year'] ?? '';

$sql = "SELECT c.id, c.model, c.year, c.colour, c.location, c.price, c.image_path, s.name as seller_name 
         FROM cars c 
         JOIN sellers s ON c.seller_id = s.id 
         WHERE 1=1";

$params = [];

if (!empty($model)) {
    $sql .= " AND c.model LIKE ?";
    $params[] = "%{$model}%";
}

if (!empty($year)) {
    $sql .= " AND c.year = ?";
    $params[] = $year;
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$cars = [];
while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Cars - AutoMarket</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo"><a href="index.html">AutoMarket</a></div>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="seller.html">Seller</a></li>
                    <li><a href="search.html">Search Cars</a></li>
                    <li><a href="login.html">Login</a></li>
                    <li><a href="register.html">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero" style="background-color: #e67e22;">
        <div class="container">
            <h1>Find Your Perfect Car</h1>
            <p>Search by model and year to find the car that fits your needs.</p>
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <div class="search-box">
                <form method="GET" action="search.php">
                    <input type="text" id="search-model" name="model" placeholder="Enter car model (e.g., Toyota, BMW)" value="<?php echo htmlspecialchars($model); ?>">
                    <input type="text" id="search-year" name="year" placeholder="Enter year (e.g., 2020)" value="<?php echo htmlspecialchars($year); ?>">
                    <button type="submit" class="btn">Search</button>
                </form>
            </div>

            <div id="search-results" class="car-list">
                <?php if (empty($cars)): ?>
                    <p style="text-align:center; padding: 20px;">No cars found matching your search criteria.</p>
                <?php else: ?>
                    <?php foreach ($cars as $car): ?>
                        <div class="car-item">
                            <img src="<?php echo htmlspecialchars($car['image_path'] ?? 'https://via.placeholder.com/400x300?text=Car+Image'); ?>" alt="<?php echo htmlspecialchars($car['model']); ?>" style="height: 200px; object-fit: cover; width: 100%;">
                            <div class="car-info">
                                <h3><?php echo htmlspecialchars($car['model']); ?></h3>
                                <p>Year: <?php echo htmlspecialchars($car['year']); ?></p>
                                <p>Color: <?php echo htmlspecialchars($car['colour']); ?></p>
                                <p>Location: <?php echo htmlspecialchars($car['location']); ?></p>
                                <p>Seller: <?php echo htmlspecialchars($car['seller_name']); ?></p>
                                <p class="price">¥<?php echo number_format($car['price'], 2); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div id="car-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2026 AutoMarket. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>