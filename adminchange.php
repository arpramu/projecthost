<!-- admin_roomlist.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Roomlist</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-image: url('https://ak-d.tripcdn.com/images/0104v1200090g4v3vAC50.jpg');
        background-size: cover;
        background-position: center;
        height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .admin-container {
        text-align: center;
        padding: 40px; /* Increased padding */
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        background-color: rgba(0, 0, 0, 0.7);
        width: 90%; /* Adjust the width as needed */
        max-width: 800px;
        margin: 0 auto;
        height: 800px;
    }

    form {
        width: 100%;
        max-width: 500px;
        margin: 40px auto; /* Increased margin */
        padding: 40px; /* Increased padding */
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        background-color: rgba(0, 0, 0, 0.7);
        align-items: center;
        height: 600px;
    }

    label, input, select {
        display: block;
        margin-bottom: 20px; /* Increased margin */
        color: white;
        text-align: center;
        margin: auto;
    }

    input[type="submit"], input[type="button"] {
        background-color: #4CAF50;
        color: white;
        padding: 15px 30px; /* Increased padding */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
    }
    </style>

</head>
<body>

<div class="admin-container">
    <h2>Admin Roomlist</h2>

    <form method="post">
        <label for="room_id">Room ID:</label>
        <input style="color:black;" type="text" name="room_id" required><br><br>

        <label for="room_name">Room Name:</label>
        <input style="color:black;" type="text" name="room_name"><br><br>

        <label for="price_per_night">Price per Night:</label>
        <input style="color:black;" type="number" name="price_per_night"><br><br>

        <label for="airconditioner">Air Conditioner:</label>
        <select style="color:black;" name="airconditioner">
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select><br>

        <label for="swimmingpool">Swimming Pool:</label>
        <select style="color:black;" name="swimmingpool">
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select><br>

        <label for="room_size">Room Size:</label>
        <select style="color:black;" name="room_size">
            <option value="large">Large</option>
            <option value="small">Small</option>
        </select><br>

        <label for="room_category">Room Category:</label>
        <select style="color:black;" name="room_category">
            <option value="economy">Economy</option>
            <option value="luxury">Luxury</option>
        </select><br>

        <input type="submit" name="insert" value="Insert"><br>
        <input type="submit" name="update" value="Update"><br>
        <input type="submit" name="delete" value="Delete"><br>
    </form>

    <?php
    // Include your PostgreSQL connection code here
    $host = 'localhost';
    $dbname = 'project';
    $user = 'postgres';
    $password = 'postgres';

    try {
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $room_id = $_POST["room_id"];
        $room_name = $_POST["room_name"];
        $price_per_night = $_POST["price_per_night"];
        $airconditioner = $_POST["airconditioner"];
        $swimmingpool = $_POST["swimmingpool"];
        $room_size = $_POST["room_size"];
        $room_category = $_POST["room_category"];

        // Check which button is clicked
        if (isset($_POST["insert"])) {
            // Insert record
            $insertQuery = $db->prepare("
                INSERT INTO roomlist (room_id, room_name, price_per_night, airconditioner, swimmingpool, room_size, room_category)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $insertQuery->execute([$room_id, $room_name, $price_per_night, $airconditioner, $swimmingpool, $room_size, $room_category]);
        } elseif (isset($_POST["update"])) {
            // Update record
            $updateQuery = $db->prepare("
                UPDATE roomlist
                SET price_per_night = ?, airconditioner = ?, swimmingpool = ?, room_size = ?, room_category = ?
                WHERE room_id = ?
            ");
            $updateQuery->execute([$price_per_night, $airconditioner, $swimmingpool, $room_size, $room_category, $room_id]);
        } elseif (isset($_POST["delete"])) {
            // Delete record
            $deleteQuery = $db->prepare("
                DELETE FROM roomlist
                WHERE room_id = ? 
            ");
            $deleteQuery->execute([$room_id]);
        }

        // Display success message
        echo "<p>Action completed successfully!</p>";
    }
    ?>
</div>

</body>
</html>
