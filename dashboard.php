<?php
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "catering";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch registered users
$result = $conn->query("SELECT COUNT(*) AS count FROM registered_users");
$registered_users = $result->fetch_assoc()['count'];

// Fetch catering requests
$result = $conn->query("SELECT COUNT(*) AS count FROM catering_requests");
$catering_requests = $result->fetch_assoc()['count'];

// Fetch confirmed requests
$result = $conn->query("SELECT COUNT(*) AS count FROM confirmed_request");
$confirmed_requests = $result->fetch_assoc()['count'];

// Fetch catering equipment
$result = $conn->query("SELECT COUNT(*) AS count FROM catering_equipment");
$catering_equipment = $result->fetch_assoc()['count'];

// Fetch food inventory
$result = $conn->query("SELECT COUNT(*) AS count FROM food_inventory");
$food_inventory = $result->fetch_assoc()['count'];

// Fetch monthly income
$result = $conn->query("SELECT COUNT(*) AS count FROM monthly_income");
$monthly_income = $result->fetch_assoc()['count'];

// Fetch staff management
$result = $conn->query("SELECT COUNT(*) AS count FROM staff_management");
$staff_management = $result->fetch_assoc()['count'];

// Fetch cancelled orders
$result = $conn->query("SELECT COUNT(*) AS count FROM cancel_requests");
$cancelled_orders = $result->fetch_assoc()['count'];

// Fetch customer feedback
$result = $conn->query("SELECT COUNT(*) AS count FROM customer_feedback");
$customer_feedback = $result->fetch_assoc()['count'];

// Fetch customer feedback
$result = $conn->query("SELECT COUNT(*) AS count FROM transportation");
$transportation = $result->fetch_assoc()['count'];

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCMS Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('image/background.jpg'); /* Replace with the actual URL */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
            overflow: auto;
        }

        .sidebar {
            width: 60px;
            height: 100vh;
            background-color: #00acc1;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            transition: width 0.3s;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            font-size: 24px;
            margin: 15px 0;
        }
        .sidebar a:hover {
            background-color: none; /* Change background color on hover */
            color: #f4f4f4; /* Change text color on hover */
        }

        .sidebar a:hover i {
            transform: scale(1.7); /* Slightly enlarge the icon on hover */
        }
        .main-content {
            margin-left: 60px;
            padding: 20px;
            background-image: url('path-to-your-image.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;   
            transition: margin-left 0.3s;
        }

        .header {
            background-color: #ffffff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header .title {
            font-size: 24px;
            font-weight: bold;
        }
        .header .user-profile {
            display: flex;
            align-items: center;
            position: relative;
        }
        .header .user-profile .notifications {
            margin-right: 20px;
            position: relative;
            cursor: pointer;
        }
        .header .user-profile .notifications .fas {
            font-size: 24px;
            color: #00acc1;
        }
        .header .user-profile .notifications .badge {
            background-color: #ff3d00;
            color: #fff;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 12px;
            position: absolute;
            top: -10px;
            right: -10px;
        }
        .header .user-profile .user-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #00acc1;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }
        .header .user-profile .dropdown {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1;
        }
        .header .user-profile .dropdown a {
            display: block;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
        }
        .header .user-profile .dropdown a:hover {
            background-color: #f4f4f4;
        }
        .header .user-profile.active .dropdown {
            display: block;
        }
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
        }
        .card .icon {
            font-size: 36px;
            margin-right: 20px;
        }
        .card.blue {
            background-color: #29b6f6;
        }
        .card.red {
            background-color: #ff7043;
        }
        .card h2 {
            margin: 0;
            font-size: 36px;
        }
        .card p {
            margin: 10px 0 0;
            font-size: 18px;
        }
        .card .view-link {
            color: #fff;
            text-decoration: underline;
            margin-top: 10px;
            display: inline-block;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 50px;
                padding-top: 10px;
            }
            .sidebar a {
                font-size: 20px;
                margin: 15px 0;
            }
            .main-content {
                margin-left: 50px;
                padding: 10px;
            }
            .header .title {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 40px;
                padding-top: 5px;
            }
            .sidebar a {
                font-size: 16px;
                margin: 10px 0;
            }
            .main-content {
                margin-left: 40px;
                padding: 5px;
            }
            .header .title {
                font-size: 18px;
            }
            .card {
                padding: 15px;
                text-align: left;
            }
            .card .icon {
                font-size: 30px;
                margin-right: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php"><i class="fas fa-dashboard"></i></a> <!-- dashboard -->
        <a href="user.php"><i class="fas fa-users"></i></a> <!-- Registered User -->
        <a href="request.php"><i class="fas fa-concierge-bell"></i></a> <!-- Catering Request -->
        <a href="confirmed.php"><i class="fas fa-check"></i></a> <!-- Confirmed Request -->
        <a href="equipment.php"><i class="fas fa-utensils"></i></a> <!-- Catering Equipment -->
        <a href="food_inventory.php"><i class="fas fa-boxes"></i></a> <!-- Food Inventory -->
        <a href="income.php"><i class="fas fa-money-bill-wave"></i></a> <!-- Monthly Income -->
        <a href="staff.php"><i class="fas fa-users-cog"></i></a> <!-- Staff Management -->
        <a href="cancel.php"><i class="fas fa-times"></i></a> <!-- Total Cancelled Orders -->
        <a href="feedback.php"><i class="fas fa-comment-dots"></i></a> <!-- Customer Feedback -->
        <a href="transport.php"><i class="fas fa-truck"></i></a> <!-- Transportation -->
    </div>
    <div class="main-content">
        <div class="header">
            <div class="title">Dashboard</div>
            <div class="user-profile">
                <div class="notifications">
                    <i class="fas fa-bell"></i>
                    <span class="badge">2</span>
                </div>
                <div class="user-icon">A</div>
                <div class="dropdown">
                    <a href="#">Profile</a>
                    <a href="#">Logout</a>
                </div>
            </div>
        </div>
        <div class="dashboard">
            <div class="card blue">
                <i class="fas fa-users icon"></i>
                <div>
                    <h2><?php echo $registered_users; ?></h2>
                    <p>Registered User</p>
                    <a href="user.php" class="view-link">View</a>
                </div>
            </div>
            <div class="card red">
                <i class="fas fa-concierge-bell icon"></i>
                <div>
                    <h2><?php echo $catering_requests; ?></h2>
                    <p>Catering Request</p>
                    <a href="request.php" class="view-link">View</a>
                </div>
            </div>
            <div class="card blue">
                <i class="fas fa-check icon"></i>
                <div>
                    <h2><?php echo $confirmed_requests; ?></h2>
                    <p>Confirmed Request</p>
                    <a href="confirmed.php" class="view-link">View</a>
                </div>
            </div>
            <div class="card red">
                <i class="fas fa-utensils icon"></i>
                <div>
                    <h2><?php echo $catering_equipment; ?></h2>
                    <p>Catering Utensils</p>
                    <a href="equipment.php" class="view-link">View</a>
                </div>
            </div>
            <div class="card blue">
                <i class="fas fa-boxes icon"></i>
                <div>
                    <h2><?php echo $food_inventory; ?></h2>
                    <p>Food Inventory</p>
                    <a href="food_inventory.php" class="view-link">View</a>
                </div>
            </div>
            <div class="card red">
                <i class="fas fa-money-bill-wave icon"></i>
                <div>
                    <h2><?php echo $monthly_income; ?></h2>
                    <p>Monthly Income</p>
                    <a href="income.php" class="view-link">View</a>
                </div>
            </div>
            <div class="card blue">
                <i class="fas fa-users-cog icon"></i>
                <div>
                    <h2><?php echo $staff_management; ?></h2>
                    <p>Staff Management</p>
                    <a href="staff.php" class="view-link">View</a>
                </div>
            </div>
            <div class="card red">
                <i class="fas fa-times icon"></i>
                <div>
                    <h2><?php echo $cancelled_orders; ?></h2>
                    <p>Total Cancelled Orders</p>
                    <a href="cancel.php" class="view-link">View</a>
                </div>
            </div>
            <div class="card blue">
                <i class="fas fa-comment-dots icon"></i>
                <div>
                    <h2><?php echo $customer_feedback; ?></h2>
                    <p>Customer Feedback</p>
                    <a href="feedback.php" class="view-link">View</a>
                </div>
            </div>
            <div class="card red">
                <i class="fas fa-truck icon"></i>
                <div>
                    <h2><?php echo $transportation; ?></h2>
                    <p>Transportation Service</p>
                    <a href="transport.php" class="view-link">View</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('.user-icon').addEventListener('click', function() {
            this.parentElement.classList.toggle('active');
        });
        document.querySelector('.notifications').addEventListener('click', function() {
            alert('Notifications clicked!');
        });
    </script>
</body>
</html>
