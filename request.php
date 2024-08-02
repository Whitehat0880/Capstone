<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "catering";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_request'])) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO catering_requests (client_name, event_name, request_date, packs, venue, start_time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $client_name, $event_name, $request_date, $packs, $venue, $start_time);

        // Set parameters and execute
        $client_name = $_POST['client_name'];
        $event_name = $_POST['event_name'];
        $request_date = $_POST['request_date'];
        $packs = $_POST['packs'];
        $venue = $_POST['venue'];
        $start_time = $_POST['start_time'];

        if ($stmt->execute()) {
            $message = "New request added successfully";
        } else {
            $message = "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } elseif (isset($_POST['confirmed_requests'])) {
        $request_id = $_POST['request_id'];

        // Move request to confirmed_requests
        $stmt = $conn->prepare("INSERT INTO confirmed_request (client_name, event_name, request_date, packs, venue, start_time) SELECT client_name, event_name, request_date, packs, venue, start_time FROM catering_requests WHERE id = ?");
        $stmt->bind_param("i", $request_id);
        if ($stmt->execute()) {
            // Delete from catering_requests
            $stmt = $conn->prepare("DELETE FROM catering_requests WHERE id = ?");
            $stmt->bind_param("i", $request_id);
            $stmt->execute();

            $message = "Request confirmed successfully";
        } else {
            $message = "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } elseif (isset($_POST['cancel_request'])) {
        $request_id = $_POST['request_id'];

        // Move request to cancel_requests
        $stmt = $conn->prepare("INSERT INTO cancel_requests (client_name, event_name, request_date, packs, venue, start_time) SELECT client_name, event_name, request_date, packs, venue, start_time FROM catering_requests WHERE id = ?");
        $stmt->bind_param("i", $request_id);
        if ($stmt->execute()) {
            // Delete from catering_requests
            $stmt = $conn->prepare("DELETE FROM catering_requests WHERE id = ?");
            $stmt->bind_param("i", $request_id);
            $stmt->execute();

            $message = "Request canceled successfully";
        } else {
            $message = "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Fetch all catering request details
$sql = "SELECT id, client_name, event_name, request_date, packs, venue, start_time FROM catering_requests";
$result = $conn->query($sql);

$records = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}

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
    <link rel="stylesheet" href="request.css">
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php"><i class="fas fa-dashboard"></i></a>
        <a href="user.php"><i class="fas fa-users"></i></a>
        <a href="request.php"><i class="fas fa-concierge-bell"></i></a>
        <a href="confirmed.php"><i class="fas fa-check"></i></a>
        <a href="cancel.php"><i class="fas fa-times"></i></a>
        <a href="equipment.php"><i class="fas fa-utensils"></i></a>
        <a href="food_inventory.php"><i class="fas fa-boxes"></i></a>
        <a href="income.php"><i class="fas fa-money-bill-wave"></i></a>
        <a href="staff.php"><i class="fas fa-users-cog"></i></a>
        <a href="feedback.php"><i class="fas fa-comment-dots"></i></a>
        <a href="transport.php"><i class="fas fa-truck"></i></a>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="title">Catering Request</div>
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

        <!-- Button to toggle the form -->
        <div class="form-container">
            <button class="open-modal-btn" id="open-modal-btn">Add New Request</button>
        </div>

        <!-- Modal for adding new requests -->
        <div id="request-modal" class="modal">
            <div class="modal-content">
                <span class="close" id="close-modal">&times;</span>
                <div class="title">Add New Request</div>
                <form action="" method="POST">
                    <label for="client_name">Client Name:</label>
                    <input type="text" id="client_name" name="client_name" required>

                    <label for="event_name">Event Name:</label>
                    <input type="text" id="event_name" name="event_name" required>

                    <label for="request_date">Request Date:</label>
                    <input type="date" id="request_date" name="request_date" required>

                    <label for="packs">Packs:</label>
                    <input type="number" id="packs" name="packs" required>

                    <label for="venue">Venue:</label>
                    <input type="text" id="venue" name="venue" required>

                    <label for="start_time">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" required>

                    <button type="submit" name="add_request">Add Request</button>
                </form>
                <?php if (isset($message)): ?>
                    <p><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Catering details section -->
        <div class="catering-details">
            <div class="title">Request Detail</div>
            <?php if (count($records) > 0): ?>
                <?php foreach ($records as $record): ?>
                    <div class="detail">
                        <div class="request-date"><?php echo date('Y-m-d'); ?></div>
                        <p><strong>Client Name:</strong> <?php echo htmlspecialchars($record['client_name']); ?></p>
                        <p><strong>Event Name:</strong> <?php echo htmlspecialchars($record['event_name']); ?></p>
                        <p><strong>Request Date:</strong> <?php echo htmlspecialchars($record['request_date']); ?></p>
                        <p><strong>Packs:</strong> <?php echo htmlspecialchars($record['packs']); ?></p>
                        <p><strong>Venue:</strong> <?php echo htmlspecialchars($record['venue']); ?></p>
                        <p><strong>Start Time:</strong> <?php echo htmlspecialchars($record['start_time']); ?></p>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $record['id']; ?>">
                            <button type="submit" name="confirmed_requests">Confirm</button>
                            <button type="submit" name="cancel_request">Cancel</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="detail">
                    <p>No records found</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        // Get modal elements
        var modal = document.getElementById("request-modal");
        var btn = document.getElementById("open-modal-btn");
        var span = document.getElementById("close-modal");

        // Open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>
</html>
