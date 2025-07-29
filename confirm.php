<?php
session_start();
include 'db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    // Validate data
    if (empty($date) || empty($time) || empty($name) || empty($email)) {
        header('Location: booking.php?error=missing_fields');
        exit;
    }
    
    // Create a new appointment
    $newAppointment = [
        'id' => time(), // Use timestamp as ID
        'title' => '30 Minute Meeting',
        'guest_name' => $name,
        'guest_email' => $email,
        'date' => $date,
        'time' => $time,
        'status' => 'confirmed',
        'notes' => $notes
    ];
    
    // Store in session
    if (!isset($_SESSION['appointments'])) {
        $_SESSION['appointments'] = [];
    }
    
    $_SESSION['appointments'][] = $newAppointment;
    $_SESSION['booking'] = $newAppointment;
} else {
    // If not submitted via POST, redirect to booking page
    header('Location: booking.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Calendly Clone</title>
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
        }
        
        /* Header Styles */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
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
        
        /* Confirmation Container */
        .confirmation-container {
            max-width: 800px;
            margin: 60px auto;
            padding: 40px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        
        .confirmation-icon {
            width: 80px;
            height: 80px;
            background-color: #10b981;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 30px;
        }
        
        .confirmation-container h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #1a2b42;
        }
        
        .confirmation-container p {
            color: #4a5568;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .booking-details {
            margin: 40px 0;
            padding: 30px;
            background-color: #f1f5f9;
            border-radius: 8px;
            text-align: left;
        }
        
        .booking-details h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #1a2b42;
            text-align: center;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 15px;
        }
        
        .detail-label {
            font-weight: 600;
            width: 120px;
            color: #4a5568;
        }
        
        .detail-value {
            flex: 1;
        }
        
        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .primary-btn {
            background-color: #0069ff;
            color: white;
        }
        
        .primary-btn:hover {
            background-color: #0052cc;
        }
        
        .secondary-btn {
            background-color: #f1f5f9;
            color: #4a5568;
        }
        
        .secondary-btn:hover {
            background-color: #e2e8f0;
        }
        
        /* Calendar Invite */
        .calendar-invite {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }
        
        .calendar-invite h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: #1a2b42;
        }
        
        .calendar-options {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .calendar-option {
            padding: 10px 15px;
            border-radius: 4px;
            background-color: #f1f5f9;
            color: #4a5568;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .calendar-option:hover {
            background-color: #e2e8f0;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                padding: 15px 20px;
            }
            
            .confirmation-container {
                padding: 20px;
                margin: 30px 15px;
            }
            
            .buttons {
                flex-direction: column;
            }
            
            .calendar-options {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <h1>Calendly</h1>
        </div>
    </header>
    
    <div class="confirmation-container">
        <div class="confirmation-icon">✓</div>
        
        <h2>Booking Confirmed!</h2>
        <p>Your appointment has been scheduled successfully.</p>
        <p>A confirmation email has been sent to <?php echo htmlspecialchars($email); ?></p>
        
        <div class="booking-details">
            <h3>Appointment Details</h3>
            
            <div class="detail-item">
                <div class="detail-label">Event:</div>
                <div class="detail-value">30 Minute Meeting</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Date:</div>
                <div class="detail-value"><?php echo htmlspecialchars($date); ?></div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Time:</div>
                <div class="detail-value"><?php echo htmlspecialchars($time); ?></div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Name:</div>
                <div class="detail-value"><?php echo htmlspecialchars($name); ?></div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Email:</div>
                <div class="detail-value"><?php echo htmlspecialchars($email); ?></div>
            </div>
            
            <?php if (!empty($notes)): ?>
            <div class="detail-item">
                <div class="detail-label">Notes:</div>
                <div class="detail-value"><?php echo nl2br(htmlspecialchars($notes)); ?></div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="calendar-invite">
            <h3>Add to Calendar</h3>
            <div class="calendar-options">
                <a href="#" class="calendar-option">Google Calendar</a>
                <a href="#" class="calendar-option">Apple Calendar</a>
                <a href="#" class="calendar-option">Outlook</a>
                <a href="#" class="calendar-option">Yahoo Calendar</a>
            </div>
        </div>
        
        <div class="buttons">
            <a href="booking.php" class="btn secondary-btn">Book Another Appointment</a>
            <a href="dashboard.php" class="btn primary-btn">View in Dashboard</a>
        </div>
    </div>
    
    <script>
        // JavaScript for calendar options
        document.querySelectorAll('.calendar-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                alert('Calendar integration would be implemented here in a real application.');
            });
        });
    </script>
</body>
</html>
