<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendly Clone - Schedule Meetings with Ease</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #ffffff;
            color: #1a2b42;
            line-height: 1.6;
        }
        
        /* Header Styles */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 40px;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin: 0 15px;
        }
        
        nav ul li a {
            text-decoration: none;
            color: #1a2b42;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        nav ul li a:hover {
            color: #0069ff;
        }
        
        .auth-buttons {
            display: flex;
            align-items: center;
        }
        
        .login-btn {
            margin-right: 15px;
            color: #1a2b42;
            text-decoration: none;
            font-weight: 500;
        }
        
        .get-started-btn {
            background-color: #0069ff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .get-started-btn:hover {
            background-color: #0052cc;
        }
        
        /* Hero Section */
        .hero {
            display: flex;
            padding: 80px 50px;
            align-items: center;
            justify-content: space-between;
        }
        
        .hero-content {
            width: 45%;
        }
        
        .hero-image {
            width: 50%;
            position: relative;
        }
        
        .hero-image img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: #1a2b42;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero p {
            font-size: 1.2rem;
            color: #4a5568;
            margin-bottom: 30px;
        }
        
        .signup-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
            max-width: 400px;
        }
        
        .signup-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        
        .signup-btn img {
            height: 20px;
            margin-right: 10px;
        }
        
        .google-btn {
            background-color: #fff;
            color: #333;
        }
        
        .google-btn:hover {
            background-color: #f8f9fa;
        }
        
        .microsoft-btn {
            background-color: #fff;
            color: #333;
        }
        
        .microsoft-btn:hover {
            background-color: #f8f9fa;
        }
        
        .or-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 15px 0;
            color: #718096;
        }
        
        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .or-divider::before {
            margin-right: 10px;
        }
        
        .or-divider::after {
            margin-left: 10px;
        }
        
        .email-signup {
            text-align: center;
            margin-top: 10px;
        }
        
        .email-signup a {
            color: #0069ff;
            text-decoration: none;
        }
        
        /* Trusted Section */
        .trusted-section {
            text-align: center;
            padding: 50px;
            background-color: #f8fafc;
        }
        
        .trusted-section h3 {
            color: #4a5568;
            font-weight: 500;
            margin-bottom: 30px;
        }
        
        .trusted-logos {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .trusted-logos img {
            height: 30px;
            margin: 15px;
            opacity: 0.7;
            transition: opacity 0.3s;
        }
        
        .trusted-logos img:hover {
            opacity: 1;
        }
        
        /* Book Now Button */
        .book-now-container {
            text-align: center;
            margin: 50px 0;
        }
        
        .book-now-btn {
            display: inline-block;
            background-color: #0069ff;
            color: white;
            padding: 15px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.2rem;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 6px rgba(0, 105, 255, 0.2);
        }
        
        .book-now-btn:hover {
            background-color: #0052cc;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 105, 255, 0.3);
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }
            
            .hero-content, .hero-image {
                width: 100%;
            }
            
            .hero-content {
                margin-bottom: 40px;
            }
            
            .signup-options {
                margin: 30px auto;
            }
        }
        
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 20px;
            }
            
            nav ul {
                margin: 20px 0;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php">
                <h1 style="color: #0069ff; font-weight: bold;">Calendly</h1>
            </a>
        </div>
        
        <nav>
            <ul>
                <li><a href="#">Product</a></li>
                <li><a href="#">Solutions</a></li>
                <li><a href="#">Enterprise</a></li>
                <li><a href="#">Pricing</a></li>
                <li><a href="#">Resources</a></li>
            </ul>
        </nav>
        
        <div class="auth-buttons">
            <a href="login.php" class="login-btn">Log In</a>
            <a href="signup.php" class="get-started-btn">Get Started</a>
        </div>
    </header>
    
    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Easy scheduling ahead</h1>
                <p>Join 20 million professionals who easily book meetings with the #1 scheduling tool.</p>
                
                <div class="signup-options">
                    <a href="#" class="signup-btn google-btn">
                        <span style="color: #4285F4;">G</span>
                        <span>Sign up with Google</span>
                    </a>
                    <a href="#" class="signup-btn microsoft-btn">
                        <span style="color: #00A4EF;">M</span>
                        <span>Sign up with Microsoft</span>
                    </a>
                    
                    <div class="or-divider">OR</div>
                    
                    <div class="email-signup">
                        <a href="signup.php">Sign up free with email. No credit card required</a>
                    </div>
                </div>
            </div>
            
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1611224885990-ab7363d1f2a9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Calendar booking interface">
            </div>
        </section>
        
        <div class="book-now-container">
            <a href="booking.php" class="book-now-btn">Book an Appointment</a>
        </div>
        
        <section class="trusted-section">
            <h3>Trusted by more than 100,000 of the world's leading organizations</h3>
            <div class="trusted-logos">
                <div>Company 1</div>
                <div>Company 2</div>
                <div>Company 3</div>
                <div>Company 4</div>
                <div>Company 5</div>
                <div>Company 6</div>
            </div>
        </section>
    </main>
    
    <script>
        // JavaScript for redirection
        document.querySelectorAll('.signup-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = 'signup.php';
            });
        });
    </script>
</body>
</html>
