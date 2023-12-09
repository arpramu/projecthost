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

$submissionMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];

    // Retrieve user_id using username and password
    $userQuery = $db->prepare("SELECT user_id FROM users WHERE username = ? AND password = ?");
    $userQuery->execute([$username, $password]);
    $userDetails = $userQuery->fetch(PDO::FETCH_ASSOC);

    if ($userDetails) {
        $user_id = $userDetails['user_id'];

        // Retrieve room_id using user_id from the reservation table
        $reservationQuery = $db->prepare("SELECT room_id FROM reservation WHERE user_id = ? LIMIT 1");
        $reservationQuery->execute([$user_id]);
        $reservationDetails = $reservationQuery->fetch(PDO::FETCH_ASSOC);

        if ($reservationDetails) {
            $room_id = $reservationDetails['room_id'];

            // Insert the review into the rating table
            $insertQuery = $db->prepare("
                INSERT INTO rating (user_id, room_id, rating, comment)
                VALUES (?, ?, ?, ?)
            ");
            $insertQuery->execute([$user_id, $room_id, $rating, $comment]);

            // Set the success message
            $submissionMessage = "Review submitted successfully!";
        } else {
            // Set the failure message
            $submissionMessage = "No reservation found for the user.";
        }
    } else {
        // Set the failure message
        $submissionMessage = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://www.holidify.com/images/cmsuploads/compressed/248688400_20230621091606.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .review-container {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
            background-color: rgba(0, 0, 0, 0.7);
        }

        form {
            max-width: 500px;
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

        /* Message display styling */
        .submission-message {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="review-container">
        <h2>Leave a Review</h2>

        <form method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input style="text-color:black" type="password" name="password" required>

            <label for="rating">Rating:</label>
            <select style="text-color:black" name="rating" required>
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select>

            <label for="comment">Comment:</label>
            <textarea style="text-color:black" name="comment" rows="4" required></textarea>

            <input type="submit" value="Submit Review">
        </form>
    </div>

    <!-- Submission message display -->
    <div class="submission-message"><?= $submissionMessage ?></div>

</body>

</html>
