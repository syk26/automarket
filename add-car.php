<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = trim($_POST['model'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $colour = trim($_POST['colour'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $price = trim($_POST['price'] ?? '');
    
    session_start();
    $seller_id = $_SESSION['seller_id'] ?? null;
    
    $errors = [];
    
    if (empty($model)) {
        $errors[] = "Model is required";
    }
    
    if (empty($year)) {
        $errors[] = "Year is required";
    } elseif (!preg_match('/^\d{4}$/', $year)) {
        $errors[] = "Year must be a valid 4-digit year";
    }
    
    if (empty($location)) {
        $errors[] = "Location is required";
    }
    
    if (empty($price)) {
        $errors[] = "Price is required";
    } elseif (!is_numeric($price) || $price < 0) {
        $errors[] = "Price must be a valid number";
    }
    
    if (!$seller_id) {
        $errors[] = "You must be logged in to add a car";
    }
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO cars (seller_id, model, year, colour, location, price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssd", $seller_id, $model, $year, $colour, $location, $price);
        
        if ($stmt->execute()) {
            header("Location: seller.html?success=Car added successfully!");
            exit();
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car - AutoMarket</title>
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

    <section class="page-content">
        <div class="container">
            <h2 class="page-title">Add Your Car</h2>
            <div class="form-container">
                <?php if (!empty($errors)): ?>
                    <div class="error-message" style="background-color: #e74c3c; color: white; padding: 10px; margin-bottom: 20px; border-radius: 4px; display: block;">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form id="add-car-form" method="POST" action="add-car.php">
                    <div class="form-group">
                        <label for="colour">Colour</label>
                        <input type="text" id="colour" name="colour" placeholder="Enter car colour" value="<?php echo htmlspecialchars($colour ?? ''); ?>">
                        <span class="error-message">Please enter a colour.</span>
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" id="model" name="model" placeholder="Enter car model (e.g., Toyota Camry)" value="<?php echo htmlspecialchars($model ?? ''); ?>">
                        <span class="error-message">Please enter a model.</span>
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="text" id="year" name="year" placeholder="Enter manufacture year (e.g., 2020)" value="<?php echo htmlspecialchars($year ?? ''); ?>">
                        <span class="error-message">Please enter a valid 4-digit year.</span>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" placeholder="Enter car location" value="<?php echo htmlspecialchars($location ?? ''); ?>">
                        <span class="error-message">Please enter a location.</span>
                    </div>
                    <div class="form-group">
                        <label for="price">Price (CNY)</label>
                        <input type="text" id="price" name="price" placeholder="Enter price in CNY" value="<?php echo htmlspecialchars($price ?? ''); ?>">
                        <span class="error-message">Please enter a valid price (numbers only).</span>
                    </div>
                    <button type="submit" class="btn" style="width: 100%;">Add Car</button>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2026 AutoMarket. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>