<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (!preg_match('/^[A-Za-z\s]+$/', $name)) {
        $errors[] = "Name must contain only alphabetical letters and spaces";
    }
    
    if (empty($address)) {
        $errors[] = "Address is required";
    } elseif (!preg_match('/^[A-Za-z0-9\s]+$/', $address)) {
        $errors[] = "Address must contain only alphanumeric characters and spaces";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match('/^1[3-9]\d{9}$/', $phone)) {
        $errors[] = "Phone number must be a valid China mobile number (11 digits starting with 1)";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/\.cn$|\.com$/i', $email)) {
        $errors[] = "Email must contain @ exactly once and end with .cn or .com";
    }
    
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (!preg_match('/^[A-Za-z0-9]{6,}$/', $username)) {
        $errors[] = "Username must be at least 6 alphanumeric characters";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (!preg_match('/^[A-Za-z0-9]{6,}$/', $password)) {
        $errors[] = "Password must be at least 6 alphanumeric characters";
    }
    
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM sellers WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = "Email or username already exists";
        }
    }
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO sellers (name, address, phone, email, username, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $address, $phone, $email, $username, $hashed_password);
        
        if ($stmt->execute()) {
            header("Location: login.html?success=Registration successful! Please login.");
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
    <title>Seller Registration - AutoMarket</title>
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
            <h2 class="page-title">Seller Registration</h2>
            <div class="form-container">
                <?php if (!empty($errors)): ?>
                    <div class="error-message" style="background-color: #e74c3c; color: white; padding: 10px; margin-bottom: 20px; border-radius: 4px; display: block;">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form id="registration-form" method="POST" action="register.php">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($name ?? ''); ?>">
                        <span class="error-message">Name must contain only alphabetical letters and spaces.</span>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" placeholder="Enter your address" value="<?php echo htmlspecialchars($address ?? ''); ?>">
                        <span class="error-message">Address must contain only alphanumeric characters and spaces.</span>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" placeholder="Enter China phone number (e.g., 13812345678)" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                        <span class="error-message">Phone number must be a valid China mobile number (11 digits starting with 1).</span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" id="email" name="email" placeholder="Enter your email (e.g., user@example.com)" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                        <span class="error-message">Email must contain @ exactly once and end with .cn or .com</span>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="At least 6 alphanumeric characters" value="<?php echo htmlspecialchars($username ?? ''); ?>">
                        <span class="error-message">Username must be at least 6 alphanumeric characters.</span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="At least 6 alphanumeric characters" value="">
                        <span class="error-message">Password must be at least 6 alphanumeric characters.</span>
                    </div>
                    <button type="submit" class="btn" style="width: 100%;">Register</button>
                </form>
                <p style="text-align: center; margin-top: 20px;">Already have an account? <a href="login.html">Login here</a></p>
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