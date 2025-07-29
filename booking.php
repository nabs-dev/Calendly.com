<?php
session_start();
include 'db.php';

// Get available dates (next 30 days)
$dates = [];
$currentDate = new DateTime();
for ($i = 0; $i < 30; $i++) {
    $date = clone $currentDate;
    $date->modify("+$i days");
    $dates[] = $date;
}

// Get available time slots
$timeSlots = [
    '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM', 
    '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM',
    '01:00 PM', '01:30 PM', '02:00 PM', '02:30 PM',
    '03:00 PM', '03:30 PM', '04:00 PM', '04:30 PM'
];

// Get selected date from URL parameter
$selectedDate = isset($_GET['date']) ? $_GET['date'] : $dates[0]->format('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment - Calendly Clone</title>
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
        
        .back-btn {
            color: #4a5568;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .back-btn:hover {
            color: #0069ff;
        }
        
        /* Main Content */
        .booking-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .booking-info {
            flex: 1;
            min-width: 300px;
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .booking-info h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #1a2b42;
        }
        
        .booking-info p {
            color: #4a5568;
            margin-bottom: 20px;
        }
        
        .host-info {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .host-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #0069ff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 15px;
        }
        
        .meeting-details {
            margin-top: 30px;
        }
        
        .meeting-details div {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .meeting-details i {
            margin-right: 15px;
            color: #4a5568;
            min-width: 20px;
            text-align: center;
        }
        
        /* Calendar Section */
        .calendar-section {
            flex: 2;
            min-width: 300px;
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .calendar-header h3 {
            font-size: 1.5rem;
            color: #1a2b42;
        }
        
        .calendar-nav {
            display: flex;
            gap: 10px;
        }
        
        .calendar-nav button {
            background-color: #f1f5f9;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer;
            color: #4a5568;
            transition: background-color 0.3s;
        }
        
        .calendar-nav button:hover {
            background-color: #e2e8f0;
        }
        
        /* Date Selection */
        .date-selection {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        
        .date-option {
            min-width: 80px;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }
        
        .date-option:hover {
            background-color: #f1f5f9;
        }
        
        .date-option.selected {
            background-color: #0069ff;
            color: white;
            border-color: #0069ff;
        }
        
        .date-option .day-name {
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .date-option .day-number {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 5px 0;
        }
        
        .date-option .month {
            font-size: 0.9rem;
        }
        
        /* Time Slots */
        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .time-slot {
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .time-slot:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
        }
        
        .time-slot.selected {
            background-color: #0069ff;
            color: white;
            border-color: #0069ff;
        }
        
        /* Form Section */
        .form-section {
            margin-top: 40px;
            display: none;
        }
        
        .form-section.active {
            display: block;
        }
        
        .form-section h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #1a2b42;
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
        
        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            font-size: 1rem;
        }
        
        .form-group input:focus, 
        .form-group textarea:focus {
            outline: none;
            border-color: #0069ff;
        }
        
        .submit-btn {
            background-color: #0069ff;
            color: white;
            padding: 12px 24px;
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
        
        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                padding: 15px 20px;
            }
            
            .booking-container {
                flex-direction: column;
            }
            
            .time-slots {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="back-btn">
            ← Back to Home
        </a>
        <div class="logo">
            <h1>Calendly</h1>
        </div>
    </header>
    
    <div class="booking-container">
        <div class="booking-info">
            <h2>30 Minute Meeting</h2>
            <p>Book a time to discuss your project or any questions you have.</p>
            
            <div class="host-info">
                <div class="host-avatar">D</div>
                <div>
                    <h3>Demo User</h3>
                    <p>Calendly Demo</p>
                </div>
            </div>
            
            <div class="meeting-details">
                <div>
                    <i>⏱️</i>
                    <span>30 minutes</span>
                </div>
                <div>
                    <i>🌐</i>
                    <span>Web conferencing details provided upon confirmation</span>
                </div>
            </div>
        </div>
        
        <div class="calendar-section">
            <div class="calendar-header">
                <h3>Select a Date & Time</h3>
                <div class="calendar-nav">
                    <button id="prev-dates">← Previous</button>
                    <button id="next-dates">Next →</button>
                </div>
            </div>
            
            <div class="date-selection">
                <?php
                $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                
                foreach ($dates as $index => $date) {
                    $dateStr = $date->format('Y-m-d');
                    $dayOfWeek = $date->format('w');
                    $dayOfMonth = $date->format('j');
                    $month = $date->format('n') - 1; // 0-based index for array
                    
                    $isSelected = $dateStr === $selectedDate ? 'selected' : '';
                    
                    echo "<div class='date-option $isSelected' data-date='$dateStr'>";
                    echo "<div class='day-name'>{$dayNames[$dayOfWeek]}</div>";
                    echo "<div class='day-number'>$dayOfMonth</div>";
                    echo "<div class='month'>{$monthNames[$month]}</div>";
                    echo "</div>";
                }
                ?>
            </div>
            
            <div class="time-slots">
                <?php foreach ($timeSlots as $timeSlot): ?>
                    <div class="time-slot" data-time="<?php echo $timeSlot; ?>">
                        <?php echo $timeSlot; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="form-section" id="booking-form">
                <h3>Complete Booking</h3>
                <form action="confirm.php" method="post">
                    <input type="hidden" id="selected-date" name="date" value="">
                    <input type="hidden" id="selected-time" name="time" value="">
                    
                    <div class="form-group">
                        <label for="name">Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Additional Notes</label>
                        <textarea id="notes" name="notes" rows="4"></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Confirm Booking</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // JavaScript for date and time selection
        document.addEventListener('DOMContentLoaded', function() {
            const dateOptions = document.querySelectorAll('.date-option');
            const timeSlots = document.querySelectorAll('.time-slot');
            const bookingForm = document.getElementById('booking-form');
            const selectedDateInput = document.getElementById('selected-date');
            const selectedTimeInput = document.getElementById('selected-time');
            
            // Date selection
            dateOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all date options
                    dateOptions.forEach(opt => opt.classList.remove('selected'));
                    
                    // Add selected class to clicked option
                    this.classList.add('selected');
                    
                    // Update hidden input
                    selectedDateInput.value = this.dataset.date;
                    
                    // Reset time selection
                    timeSlots.forEach(slot => slot.classList.remove('selected'));
                    bookingForm.classList.remove('active');
                });
            });
            
            // Time selection
            timeSlots.forEach(slot => {
                slot.addEventListener('click', function() {
                    // Remove selected class from all time slots
                    timeSlots.forEach(s => s.classList.remove('selected'));
                    
                    // Add selected class to clicked slot
                    this.classList.add('selected');
                    
                    // Update hidden input
                    selectedTimeInput.value = this.dataset.time;
                    
                    // Show booking form
                    bookingForm.classList.add('active');
                    
                    // Scroll to form
                    bookingForm.scrollIntoView({ behavior: 'smooth' });
                });
            });
            
            // Navigation buttons
            const prevButton = document.getElementById('prev-dates');
            const nextButton = document.getElementById('next-dates');
            const dateSelection = document.querySelector('.date-selection');
            
            prevButton.addEventListener('click', function() {
                dateSelection.scrollBy({ left: -300, behavior: 'smooth' });
            });
            
            nextButton.addEventListener('click', function() {
                dateSelection.scrollBy({ left: 300, behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>
