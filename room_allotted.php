<!-- room_allotted.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Allotted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://media.radissonhotels.net/image/radisson-collection-hotel-magdalena-plaza-sevilla/barlounge/16256-140766-f72183827_3xl.jpg?impolicy=HomeHero');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .allotted-container {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
            background-color: rgba(0, 0, 0, 0.7);
        }

        form {
            max-width: 700px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
            background-color: rgba(0, 0, 0, 0.7);
        }

        label, input, select {
            display: block;
            margin-bottom: 10px;
            color: white;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="allotted-container">
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

    // Retrieve room name from the URL parameter
    $room_name = isset($_GET['room_name']) ? $_GET['room_name'] : '';

    // Display room name as heading
    echo "<h2>{$room_name}</h2>";

    // Retrieve room details and price
    $query = $db->prepare("
        SELECT room_id, price_per_night
        FROM roomlist
        WHERE room_name = ?
    ");
    $query->execute([$room_name]);
    $roomDetails = $query->fetch(PDO::FETCH_ASSOC);

    if ($roomDetails) {
        // Display room details and price
        echo "<p>Room ID: {$roomDetails['room_id']}</p>";
        echo "<p>Price per Night: {$roomDetails['price_per_night']}</p>";

        // Display user_id
        $userQuery = $db->prepare("
             SELECT user_id, username
             FROM users
             WHERE user_id = (SELECT MAX(user_id) FROM users)
        ");
        $userQuery->execute();  // Adjust the WHERE clause as per your requirements
        $userDetails = $userQuery->fetch(PDO::FETCH_ASSOC);

        if ($userDetails) {
            echo "<p>User ID: {$userDetails['user_id']}</p>";
        } else {
            echo "<p>User not found or not specified.</p>";
        }

        // Reservation form
        echo "<form method='post'>";
        echo "<input type='hidden' name='user_id' value='{$userDetails['user_id']}'>";
        echo "<input type='hidden' name='room_id' value='{$roomDetails['room_id']}'>";
        echo "<input type='hidden' id='rate' name='rate' value='{$roomDetails['price_per_night']}'>";
        echo "<label for='checkin_date'>Check-in Date:</label>";
        echo "<input style='color:black' type='date' name='checkin_date' required><br>";
        echo "<label for='checkout_date'>Check-out Date:</label>";
        echo "<input style='color:black' type='date' name='checkout_date' required><br>";
        echo "<label for='number_of_days'>Number of Days:</label>";
        echo "<input style='color:black' type='text' id='days' name='number_of_days' onchange='calc()' required><br>";
        echo "<label for='total_payment'>Total Payment:</label>";
        echo "<input style='color:black' type='text' id='payment' name='total_payment' required><br>";
        echo "<input type='submit' value='Proceed payment'>";
        echo "<script>
        function calc(){
            var a=document.getElementById('days').value;
            var b=document.getElementById('rate').value;
            document.getElementById('payment').value= a*b;
        }
        </script>";
        echo "</form>";
    } else {
        echo "<p>No information available for this room.</p>";
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_POST["user_id"];
        $room_id = $_POST["room_id"];
        $checkin_date = $_POST["checkin_date"];
        $checkout_date = $_POST["checkout_date"];
        $number_of_days = $_POST["number_of_days"];
        $total_payment = $_POST["total_payment"];

        // Insert reservation information into the reservations table
        $insertQuery = $db->prepare("
            INSERT INTO reservation (user_id, room_id, check_in_date, check_out_date, num_of_days, total_payment)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $insertQuery->execute([$user_id, $room_id, $checkin_date, $checkout_date, $number_of_days, $total_payment]);
        
        // Display reservation confirmation
        echo "<p>Reservation Successful!</p>";
	
	header("Location: transaction.php");
        exit();
    }
     
    ?>
</div>

</body>
</html>
    