<?php
$host = 'localhost';
$dbname = 'project';
$user = 'postgres';
$password = 'postgres';

try {
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to fetch data and display in HTML
    function fetchData($db, $query, $containerId) {
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $tableHTML = "<h3>Answer:</h3><table border='1'><tr><th>";

        foreach ($result as $entry) {
            $tableHTML .= "<tr><td>" . $entry . "</td><td>";
        }

        $tableHTML .= "</table>";

        echo "<script>
            var answerContainer = document.getElementById('$containerId');
            answerContainer.innerHTML = '$tableHTML';
        </script>";
    }

    // Function to hide and display an HTML element
    function hideAndDisplay($containerId) {
        echo "<script>
            var answerContainer = document.getElementById('$containerId');
            if (answerContainer.style.display == 'block') {
                answerContainer.style.display = 'none';
            } else {
                answerContainer.style.display = 'block';
            }
        </script>";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort</title>
    <link rel="icon" href="IMAGES/" type="image/png">
    <link rel="stylesheet" href="admin.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>
<body>
    <header>
        <!-- Your header content here -->
    </header>

    <section class="home" id="home">
        <!-- Your home section content here -->
    </section>

    <section class="roomlistrating" id="roomlistrating">
        <h2>Frequently Asked Questions</h2>
        <div>
            <h3>Q1: Show all users </h3>
            <button class="btn" onclick="fetchAndDisplay('SELECT username FROM users ORDER BY username ASC', 'answerContainer1')">Answer</button>
            <div class="answer-container" id="answerContainer1" style="display:none"></div>
            <br><br><br>

            <h3>Q2: Show all reservations room_id </h3> 
            <button class="btn" onclick="fetchAndDisplay('SELECT r.room_id as difference FROM users u INNER JOIN reservation r ON u.user_id = r.user_id', 'answerContainer2')">Answer</button>
            <div class="answer-container" id="answerContainer2" style="display:none"></div>
        </div>
        <br><br><br>
        <!-- Continue with other FAQ items -->
    </section>

    
    <script>
        function fetchAndDisplay(query, containerId) {
            $.ajax({
                type: 'POST',
                
                data: { query: query },
                success: function (data) {
                    var answerContainer = document.getElementById(containerId);
                    answerContainer.innerHTML = data;
                    hideAndDisplay(containerId);
                }
            });
        }
    </script>
</body>
</html>
