<?php
// Connect to PostgreSQL database
$host = 'localhost';
$dbname = 'project';
$user = 'postgres';
$password = 'postgres';

$pdo = new PDO("pgsql:host=$host;dbname=$dbname;user=$user;password=$password");

// Function to insert user data into the database
function insertUser($username, $password, $email, $phoneNo, $age, $gender, $activity, $pdo) {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, phone_no, age, gender, activity) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $email, $phoneNo, $age, $gender, $activity]);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phoneNo = $_POST['phone_no'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $activity = $_POST['activity'];

    // Insert user data into the database
    insertUser($username, $password, $email, $phoneNo, $age, $gender, $activity, $pdo);

    // Redirect to roomselection.html
    header("Location: roomselection.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    
    <style>
        body {
            background-image: url('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/15/9b/44/ca/nirali-resorts.jpg?w=700&h=-1&s=1');
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
       a{
        text-decoration:none;
        color:white;
       }
        
        
    </style>
</head>
<body>
    
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone_no">Phone Number:</label>
        <input type="number" id="phone_no" name="phone_no" required>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>

        <label for="activity">Activity:</label>
        <select id="activity" name="activity" required>
            <option value="swimming">Swimming</option>
            <option value="theater">Theater</option>
            <option value="pipeline">Pipeline</option>
            <option value="campfire">Campfire</option>
            <option value="ropegame">Rope Game</option>
            <option value="boating">Boating</option>
        </select>

        <button type="submit">Submit</button>
    </form>
        
<button><a href="ap_resort.html">Back</a></button>

</body>
</html>
