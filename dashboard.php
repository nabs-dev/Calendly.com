<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user info
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Get the current page from the URL parameter
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Get appointments from session
$appointments = isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];

// Separate upcoming and past appointments
$upcoming_appointments = [];
$past_appointments = [];

// Default appointments if none exist in session
if (empty($appointments)) {
    $upcoming_appointments = [
        [
            'id' => 1,
            'title' => '30 Minute Meeting',
            'guest_name' => 'John Doe',
            'guest_email' => 'john@example.com',
            'date' => '2023-06-15',
            'time' => '10:00 AM',
            'status' => 'confirmed'
        ],
        [
            'id' => 2,
            'title' => '15 Minute Meeting',
            'guest_name' => 'Jane Smith',
            'guest_email' => 'jane@example.com',
            'date' => '2023-06-16',
            'time' => '02:30 PM',
            'status' => 'confirmed'
        ]
    ];

    $past_appointments = [
        [
            'id' => 3,
            'title' => '30 Minute Meeting',
            'guest_name' => 'Bob Johnson',
            'guest_email' => 'bob@example.com',
            'date' => '2023-06-10',
            'time' => '11:00 AM',
            'status' => 'completed'
        ]
    ];
} else {
    // Use the appointments from session
    foreach ($appointments as $appointment) {
        $appointment_date = strtotime($appointment['date']);
        $today = strtotime(date('Y-m-d'));
        
        if ($appointment_date >= $today) {
            $upcoming_appointments[] = $appointment;
        } else {
            $past_appointments[] = $appointment;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Calendly Clone</title>
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
            padding: 15px 30px;
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
            font-size: 1.5rem;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #0069ff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .user-name {
            margin-right: 15px;
        }
        
        .logout-btn {
            color: #4a5568;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .logout-btn:hover {
            color: #0069ff;
        }
        
        /* Main Content */
        .dashboard-container {
            display: flex;
            min-height: calc(100vh - 70px);
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: white;
            padding: 30px 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 12px 30px;
            color: #4a5568;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover, 
        .sidebar-menu a.active {
            background-color: #f1f5f9;
            color: #0069ff;
            border-left: 3px solid #0069ff;
        }
        
        .sidebar-menu a.active {
            font-weight: 500;
        }
        
        /* Main Content Area */
        .main-content {
            flex: 1;
            padding: 30px;
        }
        
        .page-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
            color: #1a2b42;
        }
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .stat-card h3 {
            font-size: 1rem;
            color: #4a5568;
            margin-bottom: 10px;
        }
        
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 600;
            color: #1a2b42;
        }
        
        /* Appointments Section */
        .appointments-section {
            margin-bottom: 40px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 1.5rem;
            color: #1a2b42;
        }
        
        .create-btn {
            background-color: #0069ff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .create-btn:hover {
            background-color: #0052cc;
        }
        
        /* Appointments Table */
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .appointments-table th,
        .appointments-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .appointments-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #4a5568;
        }
        
        .appointments-table tr:last-child td {
            border-bottom: none;
        }
        
        .appointments-table tr:hover {
            background-color: #f1f5f9;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-confirmed {
            background-color: #e6f7ff;
            color: #0069ff;
        }
        
        .status-completed {
            background-color: #e6ffee;
            color: #10b981;
        }
        
        .status-cancelled {
            background-color: #fff5f5;
            color: #e53e3e;
        }
        
        .action-btn {
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            margin-right: 5px;
        }
        
        .view-btn {
            background-color: #f1f5f9;
            color: #4a5568;
        }
        
        .view-btn:hover {
            background-color: #e2e8f0;
        }
        
        .cancel-btn {
            background-color: #fff5f5;
            color: #e53e3e;
        }
        
        .cancel-btn:hover {
            background-color: #fee2e2;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #718096;
        }
        
        /* Availability Page */
        .availability-container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .availability-header {
            margin-bottom: 30px;
        }
        
        .availability-header h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .availability-header p {
            color: #4a5568;
        }
        
        .availability-grid {
            display: grid;
            grid-template-columns: 150px repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .grid-header {
            font-weight: 600;
            padding: 10px;
            text-align: center;
            background-color: #f8fafc;
            border-radius: 4px;
        }
        
        .time-slot {
            padding: 10px;
            text-align: right;
            color: #4a5568;
        }
        
        .slot {
            background-color: #f1f5f9;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .slot.available {
            background-color: #e6ffee;
            color: #10b981;
        }
        
        .slot:hover {
            background-color: #e2e8f0;
        }
        
        .save-btn {
            background-color: #0069ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .save-btn:hover {
            background-color: #0052cc;
        }
        
        /* Integrations Page */
        .integrations-container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .integrations-header {
            margin-bottom: 30px;
        }
        
        .integrations-header h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .integrations-header p {
            color: #4a5568;
        }
        
        .integrations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .integration-card {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: all 0.3s;
        }
        
        .integration-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .integration-icon {
            width: 60px;
            height: 60px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .integration-card h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .integration-card p {
            color: #4a5568;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        
        .connect-btn {
            background-color: #0069ff;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }
        
        .connect-btn:hover {
            background-color: #0052cc;
        }
        
        /* Event Types Page */
        .event-types-container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .event-types-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .event-types-header h2 {
            font-size: 1.5rem;
        }
        
        .event-types-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .event-type-card {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s;
        }
        
        .event-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .event-type-card h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .event-type-card p {
            color: #4a5568;
            margin-bottom: 15px;
        }
        
        .event-type-details {
            display: flex;
            margin-bottom: 15px;
        }
        
        .event-type-detail {
            margin-right: 15px;
            display: flex;
            align-items: center;
            color: #4a5568;
            font-size: 0.9rem;
        }
        
        .event-type-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .event-type-link {
            color: #0069ff;
            font-size: 0.9rem;
            text-decoration: none;
        }
        
        .event-type-link:hover {
            text-decoration: underline;
        }
        
        .event-type-menu {
            color: #4a5568;
            cursor: pointer;
        }
        
        /* Settings Page */
        .settings-container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .settings-header {
            margin-bottom: 30px;
        }
        
        .settings-header h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .settings-header p {
            color: #4a5568;
        }
        
        .settings-form {
            max-width: 600px;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            font-size: 1rem;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #0069ff;
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                padding: 15px 0;
            }
            
            .sidebar-menu {
                display: flex;
                overflow-x: auto;
            }
            
            .sidebar-menu li {
                margin-bottom: 0;
                margin-right: 5px;
            }
            
            .sidebar-menu a {
                padding: 10px 15px;
                white-space: nowrap;
            }
            
            .sidebar-menu a:hover, 
            .sidebar-menu a.active {
                border-left: none;
                border-bottom: 3px solid #0069ff;
            }
        }
        
        @media (max-width: 768px) {
            .appointments-table {
                display: block;
                overflow-x: auto;
            }
            
            .dashboard-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo">
            <h1>Calendly</h1>
        </a>
        
        <div class="user-menu">
            <div class="user-avatar"><?php echo substr($user_name, 0, 1); ?></div>
            <span class="user-name"><?php echo htmlspecialchars($user_name); ?></span>
            <a href="logout.php" class="logout-btn">Log Out</a>
        </div>
    </header>
    
    <div class="dashboard-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php?page=dashboard" class="<?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="dashboard.php?page=availability" class="<?php echo $current_page === 'availability' ? 'active' : ''; ?>">Availability</a></li>
                <li><a href="dashboard.php?page=integrations" class="<?php echo $current_page === 'integrations' ? 'active' : ''; ?>">Integrations</a></li>
                <li><a href="dashboard.php?page=event_types" class="<?php echo $current_page === 'event_types' ? 'active' : ''; ?>">Event Types</a></li>
                <li><a href="dashboard.php?page=settings" class="<?php echo $current_page === 'settings' ? 'active' : ''; ?>">Settings</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <?php if ($current_page === 'dashboard'): ?>
                <h1 class="page-title">Dashboard</h1>
                
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <h3>Upcoming Meetings</h3>
                        <div class="stat-value"><?php echo count($upcoming_appointments); ?></div>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Past Meetings</h3>
                        <div class="stat-value"><?php echo count($past_appointments); ?></div>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Total Meetings</h3>
                        <div class="stat-value"><?php echo count($upcoming_appointments) + count($past_appointments); ?></div>
                    </div>
                </div>
                
                <section class="appointments-section">
                    <div class="section-header">
                        <h2 class="section-title">Upcoming Appointments</h2>
                        <a href="booking.php" class="create-btn">Create New Event</a>
                    </div>
                    
                    <?php if (count($upcoming_appointments) > 0): ?>
                        <table class="appointments-table">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Guest</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upcoming_appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['title']); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($appointment['guest_name']); ?><br>
                                            <small><?php echo htmlspecialchars($appointment['guest_email']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $appointment['status']; ?>">
                                                <?php echo ucfirst($appointment['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="action-btn view-btn">View</a>
                                            <a href="#" class="action-btn cancel-btn">Cancel</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>No upcoming appointments. Create a new event to get started.</p>
                        </div>
                    <?php endif; ?>
                </section>
                
                <section class="appointments-section">
                    <div class="section-header">
                        <h2 class="section-title">Past Appointments</h2>
                    </div>
                    
                    <?php if (count($past_appointments) > 0): ?>
                        <table class="appointments-table">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Guest</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($past_appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['title']); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($appointment['guest_name']); ?><br>
                                            <small><?php echo htmlspecialchars($appointment['guest_email']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $appointment['status']; ?>">
                                                <?php echo ucfirst($appointment['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="action-btn view-btn">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>No past appointments to display.</p>
                        </div>
                    <?php endif; ?>
                </section>
            
            <?php elseif ($current_page === 'availability'): ?>
                <h1 class="page-title">Availability</h1>
                
                <div class="availability-container">
                    <div class="availability-header">
                        <h2>Set Your Available Hours</h2>
                        <p>Define when you're available for meetings. Drag to select time slots.</p>
                    </div>
                    
                    <div class="availability-grid">
                        <div class="grid-header">Time</div>
                        <div class="grid-header">Monday</div>
                        <div class="grid-header">Tuesday</div>
                        <div class="grid-header">Wednesday</div>
                        <div class="grid-header">Thursday</div>
                        <div class="grid-header">Friday</div>
                        <div class="grid-header">Saturday</div>
                        <div class="grid-header">Sunday</div>
                        
                        <?php
                        $times = ['9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM'];
                        foreach ($times as $time): ?>
                            <div class="time-slot"><?php echo $time; ?></div>
                            <?php for ($day = 1; $day <= 7; $day++): ?>
                                <div class="slot <?php echo ($day <= 5) ? 'available' : ''; ?>"><?php echo ($day <= 5) ? 'Available' : ''; ?></div>
                            <?php endfor; ?>
                        <?php endforeach; ?>
                    </div>
                    
                    <button class="save-btn">Save Availability</button>
                </div>
                
            <?php elseif ($current_page === 'integrations'): ?>
                <h1 class="page-title">Integrations</h1>
                
                <div class="integrations-container">
                    <div class="integrations-header">
                        <h2>Connect Your Calendar</h2>
                        <p>Sync your meetings with your favorite calendar application.</p>
                    </div>
                    
                    <div class="integrations-grid">
                        <div class="integration-card">
                            <div class="integration-icon">G</div>
                            <h3>Google Calendar</h3>
                            <p>Connect your Google Calendar to automatically sync events.</p>
                            <a href="#" class="connect-btn">Connect</a>
                        </div>
                        
                        <div class="integration-card">
                            <div class="integration-icon">O</div>
                            <h3>Outlook Calendar</h3>
                            <p>Connect your Outlook Calendar to automatically sync events.</p>
                            <a href="#" class="connect-btn">Connect</a>
                        </div>
                        
                        <div class="integration-card">
                            <div class="integration-icon">A</div>
                            <h3>Apple Calendar</h3>
                            <p>Connect your Apple Calendar to automatically sync events.</p>
                            <a href="#" class="connect-btn">Connect</a>
                        </div>
                        
                        <div class="integration-card">
                            <div class="integration-icon">Z</div>
                            <h3>Zoom</h3>
                            <p>Add Zoom meeting links to your calendar events.</p>
                            <a href="#" class="connect-btn">Connect</a>
                        </div>
                    </div>
                </div>
                
            <?php elseif ($current_page === 'event_types'): ?>
                <h1 class="page-title">Event Types</h1>
                
                <div class="event-types-container">
                    <div class="event-types-header">
                        <h2>Manage Your Event Types</h2>
                        <a href="#" class="create-btn">Create New Event Type</a>
                    </div>
                    
                    <div class="event-types-grid">
                        <div class="event-type-card">
                            <h3>15 Minute Meeting</h3>
                            <p>Short meeting for quick discussions.</p>
                            <div class="event-type-details">
                                <div class="event-type-detail">15 min</div>
                                <div class="event-type-detail">One-on-One</div>
                            </div>
                            <div class="event-type-actions">
                                <a href="#" class="event-type-link">Copy Link</a>
                                <div class="event-type-menu">•••</div>
                            </div>
                        </div>
                        
                        <div class="event-type-card">
                            <h3>30 Minute Meeting</h3>
                            <p>Standard meeting for most discussions.</p>
                            <div class="event-type-details">
                                <div class="event-type-detail">30 min</div>
                                <div class="event-type-detail">One-on-One</div>
                            </div>
                            <div class="event-type-actions">
                                <a href="#" class="event-type-link">Copy Link</a>
                                <div class="event-type-menu">•••</div>
                            </div>
                        </div>
                        
                        <div class="event-type-card">
                            <h3>60 Minute Meeting</h3>
                            <p>Extended meeting for in-depth discussions.</p>
                            <div class="event-type-details">
                                <div class="event-type-detail">60 min</div>
                                <div class="event-type-detail">One-on-One</div>
                            </div>
                            <div class="event-type-actions">
                                <a href="#" class="event-type-link">Copy Link</a>
                                <div class="event-type-menu">•••</div>
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php elseif ($current_page === 'settings'): ?>
                <h1 class="page-title">Settings</h1>
                
                <div class="settings-container">
                    <div class="settings-header">
                        <h2>Account Settings</h2>
                        <p>Manage your account preferences and profile information.</p>
                    </div>
                    
                    <form class="settings-form">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_name); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="timezone">Timezone</label>
                            <select id="timezone" name="timezone">
                                <option value="UTC-8">Pacific Time (UTC-8)</option>
                                <option value="UTC-5">Eastern Time (UTC-5)</option>
                                <option value="UTC+0">Greenwich Mean Time (UTC+0)</option>
                                <option value="UTC+1">Central European Time (UTC+1)</option>
                                <option value="UTC+5:30">Indian Standard Time (UTC+5:30)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="date_format">Date Format</label>
                            <select id="date_format" name="date_format">
                                <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                                <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                                <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="save-btn">Save Settings</button>
                    </form>
                </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script>
        // JavaScript for sidebar menu and actions
        document.addEventListener('DOMContentLoaded', function() {
            // Action buttons
            const viewButtons = document.querySelectorAll('.view-btn');
            const cancelButtons = document.querySelectorAll('.cancel-btn');
            
            viewButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert('View appointment details would open here.');
                });
            });
            
            cancelButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to cancel this appointment?')) {
                        alert('Appointment cancelled successfully!');
                    }
                });
            });
            
            // Availability page
            const slots = document.querySelectorAll('.slot');
            slots.forEach(slot => {
                slot.addEventListener('click', function() {
                    this.classList.toggle('available');
                    if (this.classList.contains('available')) {
                        this.textContent = 'Available';
                    } else {
                        this.textContent = '';
                    }
                });
            });
            
            // Save buttons
            const saveButtons = document.querySelectorAll('.save-btn');
            saveButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert('Settings saved successfully!');
                });
            });
            
            // Integration connect buttons
            const connectButtons = document.querySelectorAll('.connect-btn');
            connectButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert('Integration connection would happen here.');
                });
            });
        });
    </script>
</body>
</html>
