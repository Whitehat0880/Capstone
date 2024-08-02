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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['quantity']) && isset($_POST['type'])) {
        $name = $_POST['name'];
        $quantity = $_POST['quantity'];
        $type = $_POST['type'];

        $sql = "INSERT INTO catering_equipment (name, quantity, type) VALUES ('$name', '$quantity', '$type')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>New record created successfully</p>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    } elseif (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $sql = "DELETE FROM catering_equipment WHERE id='$delete_id'";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
        exit;
    }
}

// Fetch utensils and equipment
$utensils = [];
$equipment = [];

$sql = "SELECT id, name, quantity, type FROM catering_equipment ORDER BY name ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row['type'] === 'utensils') {
            $utensils[] = $row;
        } elseif ($row['type'] === 'equipment') {
            $equipment[] = $row;
        }
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
    <link rel="stylesheet" href="equipment.css">
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
    </div>
    <div class="main-content">
        <div class="header">
            <div class="title">Utensils and Equipment</div>
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
        
        <!-- Toggle button for the form -->
        <button class="toggle-button">Add New Item</button>

        <!-- Add item form -->
        <div class="form-container">
            <form method="POST" action="">
                <input type="text" name="name" placeholder="Name" required>
                <input type="number" name="quantity" placeholder="Quantity" required>
                <select name="type" required>
                    <option value="">Select Type</option>
                    <option value="utensils">Utensils</option>
                    <option value="equipment">Equipment</option>
                </select>
                <button type="submit">Add Item</button>
            </form>
        </div>

        <!-- Utensils Table -->
        <div class="table-container">
            <div class="title">Utensils</div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utensils as $utensil): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($utensil['name']); ?></td>
                            <td><?php echo htmlspecialchars($utensil['quantity']); ?></td>
                            <td class="action-buttons">
                                <button class="update-button" data-id="<?php echo $utensil['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Equipment Table -->
        <div class="table-container">
            <div class="title">Equipment</div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($equipment as $equip): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($equip['name']); ?></td>
                            <td><?php echo htmlspecialchars($equip['quantity']); ?></td>
                            <td class="action-buttons">
                                <button class="update-button" data-id="<?php echo $equip['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.querySelector('.user-icon').addEventListener('click', function() {
            this.parentElement.classList.toggle('active');
        });
        document.querySelector('.notifications').addEventListener('click', function() {
            alert('Notifications clicked!');
        });

        document.querySelector('.toggle-button').addEventListener('click', function() {
            const formContainer = document.querySelector('.form-container');
            if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                formContainer.style.display = 'block';
            } else {
                formContainer.style.display = 'none';
            }
        });

        document.querySelectorAll('.update-button').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                // Implement your update logic here (e.g., show an update form or redirect to an update page)
                alert('Update functionality is not yet implemented for ID: ' + id);
            });
        });
    </script>
</body>
</html>