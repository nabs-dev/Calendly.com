<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        // For demo purposes, accept any email and password
        // In a real application, you would verify against database
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = explode('@', $email)[0]; // Use part before @ as name
        $_SESSION['user_email'] = $email;
        
        // Redirect to dashboard
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Calendly Clone</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            color: #1a2b42;
            line-height: 1.6;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header Styles */
        header {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo h1 {
            color: #0069ff;
            font-weight: bold;
        }
        
        /* Login Container */
        .login-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-form {
            width: 100%;
            max-width: 400px;
            background-color: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .login-form h2 {
            font-size: 1.8rem;
            margin-bottom: 30px;
            color: #1a2b42;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #4a5568;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            font-size: 1rem;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #0069ff;
        }
        
        .error-message {
            color: #e53e3e;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .submit-btn {
            width: 100%;
            background-color: #0069ff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #0052cc;
        }
        
        .form-footer {
            margin-top: 30px;
            text-align: center;
        }
        
        .form-footer a {
            color: #0069ff;
            text-decoration: none;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .login-form {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo">
            <h1>Calendly</h1>
        </a>
    </header>
    
    <div class="login-container">
        <div class="login-form">
            <h2>Log In to Your Account</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="submit-btn">Log In</button>
                
                <div class="form-footer">
                    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                    <p><a href="#">Forgot password?</a></p>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // No pre-filled values, let user enter any credentials
    </script>
</body>
</html>
