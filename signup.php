<?php
session_start();
include 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } else {
        // In a real application, you would insert into database
        // For demo purposes, we'll just show a success message
        $success = 'Account created successfully! You can now log in.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Calendly Clone</title>
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
            min-height: 100vh;
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
        
        /* Signup Container */
        .signup-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .signup-form {
            width: 100%;
            max-width: 500px;
            background-color: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .signup-form h2 {
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
        
        .success-message {
            color: #10b981;
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
        
        .terms {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #718096;
            text-align: center;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .signup-form {
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
    
    <div class="signup-container">
        <div class="signup-form">
            <h2>Create Your Account</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form action="signup.php" method="post">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="submit-btn">Sign Up</button>
                
                <div class="terms">
                    By signing up, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                </div>
                
                <div class="form-footer">
                    <p>Already have an account? <a href="login.php">Log in</a></p>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // JavaScript for form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            form.addEventListener('submit', function(e) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                }
                
                if (password.value.length < 8) {
                    e.preventDefault();
                    alert('Password must be at least 8 characters long!');
                }
            });
        });
    </script>
</body>
</html>
