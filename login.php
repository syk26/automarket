<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM sellers WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['seller_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                header("Location: seller.html?success=Login successful!");
                exit();
            } else {
                $errors[] = "Invalid username or password";
            }
        } else {
            $errors[] = "Invalid username or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Login - AutoMarket</title>
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
            <h2 class="page-title">Seller Login</h2>
            <div class="form-container">
                <?php if (!empty($errors)): ?>
                    <div class="error-message" style="background-color: #e74c3c; color: white; padding: 10px; margin-bottom: 20px; border-radius: 4px; display: block;">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form id="login-form" method="POST" action="login.php">
                    <div class="form-group">
                        <label for="login-username">Username</label>
                        <input type="text" id="login-username" name="username" placeholder="Enter your username" value="<?php echo htmlspecialchars($username ?? ''); ?>">
                        <span class="error-message">Invalid username format.</span>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" id="login-password" name="password" placeholder="Enter your password (min 6 chars)" value="">
                        <span class="error-message">Invalid password format.</span>
                    </div>
                    <button type="submit" class="btn" style="width: 100%;">Login</button>
                </form>
                <p style="text-align: center; margin-top: 20px;">Don't have an account? <a href="register.html">Register here</a></p>
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