<!-- transaction_details.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://www.paisabazaar.com/wp-content/uploads/2018/08/credit-card-fee-768x511.jpg'); /* Replace with your image URL */
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .transaction-container {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
            background-color: rgba(0, 0, 0, 0.7);
        }

        form {
            max-width: 400px;
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

<div class="transaction-container">
    <h2>Transaction Details</h2>
    
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

    // Retrieve user_id from the users table
    $userQuery = $db->prepare("
        SELECT user_id
        FROM users
        WHERE user_id = (SELECT MAX(user_id) FROM users)
    ");
    $userQuery->execute();  
    $userDetails = $userQuery->fetch(PDO::FETCH_ASSOC);

    if ($userDetails) {
        $user_id = $userDetails['user_id'];

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data
            $payment_method = $_POST["payment_method"];
            $card_number = $_POST["card_number"];
            $expiry_date = $_POST["expiry_date"];
            $cvv = $_POST["cvv"];

            // Insert transaction details into the payment_method table
            $insertQuery = $db->prepare("
                INSERT INTO payment_method (user_id, payment_method, card_number, expiry_date, cvv)
                VALUES (?, ?, ?, ?, ?)
            ");
            $insertQuery->execute([$user_id, $payment_method, $card_number, $expiry_date, $cvv]);

            // Redirect to successful.html
            header("Location: successful.php");
            exit();
        }

        // Display the form
        echo "<form method='post'>";
        echo "<label for='payment_method'>Payment Method:</label>";
        echo "<select style='color:black' name='payment_method' required>";
        echo "<option value='card'>Card</option>";
        echo "<option value='cash'>Cash</option>";
        echo "<option value='netbanking'>Netbanking</option>";
        echo "</select><br>";
        echo "<label for='card_number'>Card Number:</label>";
        echo "<input style='color:black' type='text' name='card_number' required><br>";
        echo "<label for='expiry_date'>Expiry Date:</label>";
        echo "<input style='color:black' type='date' name='expiry_date' required><br>";
        echo "<label for='cvv'>CVV:</label>";
        echo "<input style='color:black' type='text' name='cvv' required><br>";
        echo "<input type='submit' value='Submit'>";
        echo "</form>";
    } else {
        echo "<p>User not found or not specified.</p>";
    }
    ?>
</div>

</body>
</html>
