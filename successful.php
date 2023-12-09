<!-- successful.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://www.itchotels.com/content/dam/itchotels/in/umbrella/welcomHotel/hotels/welcomhotelbayisland-port-blair/images/overview-landing-page/headmast/desktop/aerial-view.png'); /* Replace with your image URL */
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .success-container {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
            background-color: rgba(0, 0, 0, 0.7);
        }

        h2 {
            margin-bottom: 20px;
        }

        .button-container {
            margin-top: 20px;
        }

        a {
            text-decoration: none;
            color: white;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="success-container">
    <h2>-----REGISTRATION SUCCESSFUL----</h2>
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

    // Fetch the username using the maximum user_id
    $userQuery = $db->query("
        SELECT username
        FROM users
        WHERE user_id = (SELECT MAX(user_id) FROM users)
    ");

    $userDetails = $userQuery->fetch(PDO::FETCH_ASSOC);

    if ($userDetails) {
        $username = $userDetails['username'];
        echo "<p>Thank you, $username, for your payment!</p>";
    } else {
        echo "<p>Thank you for your payment!</p>";
    }
    ?>

    <div class="button-container">
        <a href="ap_resort.html">Go to Home Page</a>
    </div>
</div>

</body>
</html>
