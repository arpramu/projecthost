<?php
$host = 'localhost';
$dbname = 'project';
$user = 'postgres';
$password = 'postgres';

try {
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Example: Fetch all user names from the 'users' table
    $query1 = $db->prepare("SELECT username FROM users ORDER BY username ASC");
    $query1->execute();
    $userNames = $query1->fetchAll(PDO::FETCH_COLUMN);

    $query2 = $db->prepare("SELECT r.room_id as difference 
        FROM users u
        INNER JOIN reservation r ON u.user_id = r.user_id;
    ");
    $query2->execute();
    $userReservations = $query2->fetchAll(PDO::FETCH_COLUMN);

    $query3 = $db->prepare("SELECT COALESCE(username, 'No user') AS username
        FROM users
        WHERE user_id = (SELECT COALESCE(user_id, 0) FROM reservation ORDER BY user_id DESC LIMIT 1);
    ");
    $query3->execute();
    $userq3 = $query3->fetchAll(PDO::FETCH_COLUMN);

    $query4 = $db->prepare("SELECT r.reservation_id FROM reservation r
        JOIN users u ON r.user_id = u.user_id
        JOIN roomlist rl ON r.room_id = rl.room_id;
    ");
    $query4->execute();
    $userq4 = $query4->fetchAll(PDO::FETCH_COLUMN);

    $query5 = $db->prepare("SELECT COALESCE(price_per_night, 0) AS price_per_night
        FROM roomlist
        WHERE price_per_night = (SELECT COALESCE(MAX(price_per_night), 0) FROM roomlist);
    ");
    $query5->execute();
    $userq5 = $query5->fetchAll(PDO::FETCH_COLUMN);

    $query6 = $db->prepare("SELECT COALESCE(SUM(price_per_night), 0) AS total_cost
        FROM reservation r
        JOIN roomlist rl ON r.room_id = rl.room_id;
    ");
    $query6->execute();
    $userq6 = $query6->fetchAll(PDO::FETCH_COLUMN);
    //q7
    $query7 = $db->prepare("SELECT DISTINCT u.username
        FROM users u
        JOIN reservation r ON u.user_id = r.user_id
        JOIN roomlist rl ON r.room_id = rl.room_id
        WHERE rl.room_category = 'luxury';
    ");
    $query7->execute();
    $userq7 = $query7->fetchAll(PDO::FETCH_COLUMN);

    // q8
    $queryq8 = $db->prepare("SELECT u.username
        FROM users u
        JOIN (
            SELECT user_id, COUNT(*) as num_reservations
            FROM reservation
            GROUP BY user_id
            ORDER BY num_reservations DESC
            LIMIT 1
        ) r ON u.user_id = r.user_id;
    ");
    $queryq8->execute();
    $userq8 = $queryq8->fetchAll(PDO::FETCH_COLUMN);
    //q9
    $queryq9 = $db->prepare("SELECT  COUNT(*) as num_reservations
    FROM reservation r
    GROUP BY EXTRACT(MONTH FROM r.check_in_date);
    
");
$queryq9->execute();
$userq9 = $queryq9->fetchAll(PDO::FETCH_COLUMN);
    
//q10
$queryq10 = $db->prepare(" SELECT  u.username FROM users u JOIN 
payment_method pm ON u.user_id = pm.user_id
WHERE pm.payment_method ='card';

");
$queryq10->execute();
$userq10 = $queryq10->fetchAll(PDO::FETCH_COLUMN);
//q11
$queryq11 = $db->prepare(" SELECT u.username
FROM users u
LEFT JOIN payment_method pm ON u.user_id = pm.user_id
WHERE pm.payment_id IS NULL;




");
$queryq11->execute();
$userq11 = $queryq11->fetchAll(PDO::FETCH_COLUMN);

//q12
$queryq12 = $db->prepare("SELECT rl.room_name
FROM roomlist rl
LEFT JOIN reservation r ON rl.room_id = r.room_id
WHERE r.reservation_id IS NULL;

");
$queryq12->execute();
$userq12 = $queryq12->fetchAll(PDO::FETCH_COLUMN);
//q13
$queryq13 = $db->prepare("SELECT DISTINCT u.username
FROM users u
JOIN reservation r ON u.user_id = r.user_id
WHERE r.check_in_date >= CURRENT_DATE - INTERVAL '1 month';

");
$queryq13->execute();
$userq13 = $queryq13->fetchAll(PDO::FETCH_COLUMN);
//q14
$queryq14 = $db->prepare("SELECT rl.room_name
FROM roomlist rl
LEFT JOIN reservation r ON rl.room_id = r.room_id
WHERE r.check_in_date NOT BETWEEN '2023-01-01' AND '2023-01-10'
AND r.check_out_date NOT BETWEEN '2023-01-01' AND '2023-01-10'
GROUP BY rl.room_name;

");
$queryq14->execute();
$userq14 = $queryq14->fetchAll(PDO::FETCH_COLUMN);

//q15
$queryq15 = $db->prepare("SELECT 
COUNT(r.reservation_id) AS reservations_made
FROM roomlist rl
LEFT JOIN reservation r ON rl.room_id = r.room_id
WHERE r.check_in_date NOT BETWEEN '2023-01-01' AND '2023-01-10'
AND r.check_out_date NOT BETWEEN '2023-01-01' AND '2023-01-10'
GROUP BY rl.room_name;


");
$queryq15->execute();
$userq15 = $queryq15->fetchAll(PDO::FETCH_COLUMN);

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
    <title>admin</title>
    <link rel="icon" href="IMAGES/" type="image/png">
    <!-- CSS -->
    <link rel="stylesheet" href="admin.css">
    <!-- BOX ICONS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
        
            .reser {
    text-align: center;
 
}

thead{
    background-color: black;
    color: white;
}

tbody{
    background-color: aliceblue;
    color: blue;
}
.answer-container {
    text-align: center;
    margin: 0 auto;
    width:70%; /* This centers the container horizontally */
}

/* Optional: Add some styling to your tables */
.answer-container table {
    width: 60%; /* Adjust the width as needed */
    border-collapse: collapse;
    margin: 20px auto; /* Center the table within the container */
}

.answer-container th, .answer-container td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

/* Optional: Style the buttons */
.btn {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn:hover {
    background-color: #45a049;
}

.home{
    width: 117%;
    min-height: 80vh;
    background: url(data:image/jpeg;base64,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);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(17rem, auto));
    align-items: center;
    gap: 1.5rem;
}


      

    </style>
    

</head>

  <body>
    <!-- Navigation bar -->
    <header>
        <a href="ap_resort.html" class="logo">
            <img src="images/log.png"  alt="logohere">
        </a>
        <!-- Menu-Icon -->
        <i class="bx bx-menu" id="menu-icon"></i>
        <!-- Links -->
        <ul class="navbar">
        

            <li><a href="#roomlistrating">Roomlist and Rating Tables:</a></li>

            <li><a href="sample.php">Admin view</a></li>
           
        </ul>
        <!-- Icons -->
        <div class="header-icon">
            <a href="ap_resort.html">
                <iconify-icon icon="mdi-light:logout" style="color: white;"></iconify-icon>
            </a>

            <i class='' id="search-icon"></i>
        </div>
        <!-- Search Box -->
    </header>
       <!--Home-->
       <section class="home" id="home">
        <div class="home-text">
            <h1>WELCOME<br> ADMIN</h1>
        
        
    </section>


    <section class="roomlistrating" id="roomlistrating">
       <div class="reser">
            <h2>Frequently Asked Questions</h2>
            <div>
                <h3>Q1: Show all users </h3>
                <button class="btn" onclick="q1()" id="q1">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer1" style="display:none"></div>
            <br><br><br>

            
            <h3>Q2: Show all reservations room_id </h3> 
                <button class="btn" onclick="q2()" id="q2">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer2" style="display:none"></div>
           
            <br><br><br>
            
            <h3>Q3:  Retrieve the username of the user who made the highest reservation. </h3> 
                <button class="btn" onclick="q3()" id="q3">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer3" style="display:none"></div>
           
            
            <br><br><br>
            
            <h3>Q4: Retrieve the reservation details along with the room information. </h3> 
                <button class="btn" onclick="q4()" id="q4">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer4" style="display:none"></div>
            
            <br><br><br>
            <h3>Q5:  The highest price : </h3> 
                <button class="btn" onclick="q5()" id="q5">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer5" style="display:none"></div>
        
            <br><br><br>
            <h3>Q6: The total casy of all reservaton : </h3> 
                <button class="btn" onclick="q6()" id="q6">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer6" style="display:none"></div>
          
            <br><br><br>
            <h3>Q7: Whose  are book the luxury in revervation list : </h3> 
                <button class="btn" onclick="q7()" id="q7">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer7" style="display:none"></div>
          
            <br><br><br>
            <h3>Q8: who made the highest number of reservations: </h3> 
                <button class="btn" onclick="q8()" id="q8">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer8" style="display:none"></div>
            
            <br><br><br>
            <h3>Q9: Count the number of reservations for each month: </h3> 
                <button class="btn" onclick="q9()" id="q9">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer9" style="display:none"></div>
            
            <br><br><br>
            <h3>Q10:who have made reservations with a specific payment method(credit card): </h3> 
                <button class="btn" onclick="q10()" id="q10">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer10" style="display:none"></div>
                <br><br><br>
                <h3>Q11:Average amount of the reservation: </h3> 
                <button class="btn" onclick="q11()" id="q11">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer11" style="display:none"></div>
                <br><br><br>
                <h3>Q12:List the rooms that have never been reserved: </h3> 
                <button class="btn" onclick="q12()" id="q12">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer12" style="display:none"></div>
                <br><br><br>
                <h3>Q13:who made a reservation in the last month: </h3> 
                <button class="btn" onclick="q13()" id="q13">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer13" style="display:none"></div>
                <br><br><br>
                <h3>Q14:List the rooms and their availability : </h3> 
                <button class="btn" onclick="q14()" id="q14">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer14" style="display:none"></div>
                <br><br><br>
                <h3>Q15:List the rooms and their availability for a given date range: </h3> 
                <button class="btn" onclick="q15()" id="q15">Answer</button>
                <!-- Container to display user names -->
                <div class="answer-container" id="answerContainer15" style="display:none"></div>
                <br><br><br>






            </div>

</div>
        
        
        <br><br><br>
        
    </section>

    <!-- Add your existing body content here -->

    <script>
    function q1() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userNames); ?>;

        // Create an HTML table to display user names
        var answerContainer = document.getElementById('answerContainer1');
        var tableHTML = "<h3>Answer:</h3><table border='1'><thead><tr><th>User Names</th></tr><thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (name) {
            tableHTML += "<tr><td>" + name + "</td></tr>";
        });

        // Close the table HTML
        tableHTML += "<tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer.innerHTML = tableHTML;

    }

    function hideUsers() {
        q1();
        var answerContainer = document.getElementById('answerContainer1');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q1');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideUsers);

    
    function q2() {
            // Fetched user reservations from PHP (passed as JSON)
            var userReservations = <?php echo json_encode($userReservations); ?>;

            // Create an HTML table to display user reservations
            var answerContainer2 = document.getElementById('answerContainer2');
            var tableHTML2 = "<h3>Answer:</h3><table border='1'><thead><tr><th>Room Id</th></tr></thead><tbody>";

            userReservations.forEach(function (entry) {
                tableHTML2 += "<tr><td>" + entry + "</td></tr>" ;
            });

            // Close the table HTML
            tableHTML2 += "</tbody></table>";

            // Set the HTML content to the answer container
            answerContainer2.innerHTML = tableHTML2;
        

        
        }

        function hideReservations() {
            q2();
            
            var answerContainer2 = document.getElementById('answerContainer2');
            if(answerContainer2.style.display == 'block'){
            answerContainer2.style.display = 'none';
        }else{
            answerContainer2.style.display = 'block';
        }
        }

        var answerButton2 = document.getElementById('q2');
        answerButton2.addEventListener('mouseover', q2);

        answerButton2.addEventListener('click', hideReservations);
        //q3

        function q3() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq3); ?>;

        // Create an HTML table to display user names
        var answerContainer = document.getElementById('answerContainer3');
        var tableHTML3= "<h3>Answer:</h3><table border='1'><thead><tr><th>User Names</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (name) {
            tableHTML3 += "<tr><td>" + name + "</td></tr>";
        });

        // Close the table HTML
        tableHTML3 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer3.innerHTML = tableHTML3;

    }

    function hideq3() {
        q3();
        var answerContainer = document.getElementById('answerContainer3');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q3');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq3);
//q4

function q4() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq4); ?>;

        // Create an HTML table to display user names
        var answerContainer4 = document.getElementById('answerContainer4');
        var tableHTML4 = "<h3>Answer:</h3><table border='1'><thead><tr><th>reservation_id</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (reservatio_id) {
            tableHTML4 += "<tr><td>" + reservatio_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML4 += "<tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer4.innerHTML = tableHTML4;

    }

    function hideq4() {
        q4();
        var answerContainer = document.getElementById('answerContainer4');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q4');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq4);
//q5

function q5() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq5); ?>;

        // Create an HTML table to display user names
        var answerContainer5 = document.getElementById('answerContainer5');
        var tableHTML5 = "<h3>Answer:</h3><table border='1'><thead><tr><th>maximum _price</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (room_id) {
            tableHTML5 += "<tr><td>" + room_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML5 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer5.innerHTML = tableHTML5;

    }

    function hideq5() {
        q5();
        var answerContainer = document.getElementById('answerContainer5');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q5');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq5);
    
    //q6
    function q6() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq6); ?>;

        // Create an HTML table to display user names
        var answerContainer6 = document.getElementById('answerContainer6');
        var tableHTML6 = "<h3>Answer:</h3><table border='1'><thead><tr><th>Total cast of all reservation :</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (room_id) {
            tableHTML6 += "<tr><td>" + room_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML6 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer6.innerHTML = tableHTML6;

    }

    function hideq6() {
        q6();
        var answerContainer = document.getElementById('answerContainer6');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q6');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq6);
    
    //q7
        function q7() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq7); ?>;

        // Create an HTML table to display user names
        var answerContainer7 = document.getElementById('answerContainer7');
        var tableHTML7 = "<h3>Answer:</h3><table border='1'><thead><tr><th>name:</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (room_id) {
            tableHTML7 += "<tr><td>" + room_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML7 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer7.innerHTML = tableHTML7;

    }

    function hideq7() {
        q7();
        var answerContainer = document.getElementById('answerContainer7');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q7');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq7);
    //q8
    function q8() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq8); ?>;

        // Create an HTML table to display user names
        var answerContainer8 = document.getElementById('answerContainer8');
        var tableHTML8= "<h3>Answer:</h3><table border='1'><thead><tr><th>name:</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (room_id) {
            tableHTML8 += "<tr><td>" + room_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML8 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer8.innerHTML = tableHTML8;

    }

    function hideq8() {
        q8();
        var answerContainer = document.getElementById('answerContainer8');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q8');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq8);
    //q9
    function q9() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq9); ?>;

        // Create an HTML table to display user names
        var answerContainer9 = document.getElementById('answerContainer9');
        var tableHTML9= "<h3>Answer:</h3><table border='1'><thead><tr><th>name:</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (room_id) {
            tableHTML9 += "<tr><td>" + room_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML9 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer9.innerHTML = tableHTML9;

    }

    function hideq9() {
        q9();
        var answerContainer = document.getElementById('answerContainer9');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q9');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq9);

    //q10
    function q10() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq10); ?>;

        // Create an HTML table to display user names
        var answerContainer10 = document.getElementById('answerContainer10');
        var tableHTML10= "<h3>Answer:</h3><table border='1'><thead><tr><th> using: credit card</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (room_id) {
            tableHTML10 += "<tr><td>" + room_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML10 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer10.innerHTML = tableHTML10;

    }

    function hideq10() {
        q10();
        var answerContainer = document.getElementById('answerContainer10');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q10');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq10);
//q11

function q11() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq11); ?>;

        // Create an HTML table to display user names
        var answerContainer11= document.getElementById('answerContainer11');
        var tableHTML11= "<h3>Answer:</h3><table border='1'><thead><tr><th> using: credit card</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (room_id) {
            tableHTML11 += "<tr><td>" + room_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML11 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer11.innerHTML = tableHTML11;

    }

    function hideq11() {
        q11();
        var answerContainer = document.getElementById('answerContainer11');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q11');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq11);

    //q12
    
function q12() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq12); ?>;

        // Create an HTML table to display user names
        var answerContainer12= document.getElementById('answerContainer12');
        var tableHTML12= "<h3>Answer:</h3><table border='1'><thead><tr><th> using: room_name</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (room_id) {
            tableHTML12 += "<tr><td>" + room_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML12 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer12.innerHTML = tableHTML12;

    }

    function hideq12() {
        q12();
        var answerContainer = document.getElementById('answerContainer12');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q12');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq12);

    //q13
     
function q13() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq13); ?>;

        // Create an HTML table to display user names
        var answerContainer13= document.getElementById('answerContainer13');
        var tableHTML13= "<h3>Answer:</h3><table border='1'><thead><tr><th> using: username</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (room_id) {
            tableHTML13 += "<tr><td>" + room_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML13 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer13.innerHTML = tableHTML13;

    }

    function hideq13() {
        q13();
        var answerContainer = document.getElementById('answerContainer13');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q13');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq13);
    //14
        
function q14() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq14); ?>;

        // Create an HTML table to display user names
        var answerContainer14= document.getElementById('answerContainer14');
        var tableHTML14= "<h3>Answer:</h3><table border='1'><thead><tr><th> using: room_name</th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (room_id) {
            tableHTML14 += "<tr><td>" + room_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML14 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer14.innerHTML = tableHTML14;

    }

    function hideq14() {
        q14();
        var answerContainer = document.getElementById('answerContainer14');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q14');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq14);
    //q15
            
function q15() {
        // Fetched user names from PHP (passed as JSON)
        var userNames = <?php echo json_encode($userq15); ?>;

        // Create an HTML table to display user names
        var answerContainer15= document.getElementById('answerContainer15');
        var tableHTML15= "<h3>Answer:</h3><table border='1'><thead><tr><th> count </th></tr></thead><tbody>";

        // Add each user name to the table
        userNames.forEach(function (room_id) {
            tableHTML15 += "<tr><td>" + room_id + "</td></tr>";
        });

        // Close the table HTML
        tableHTML15 += "</tbody></table>";

        // Set the HTML content to the answer container  
        answerContainer15.innerHTML = tableHTML15;

    }

    function hideq15() {
        q15();
        var answerContainer = document.getElementById('answerContainer15');
        if(answerContainer.style.display == 'block'){
            answerContainer.style.display = 'none';
        }else{
            answerContainer.style.display = 'block';
        }

    }

    // Attach event listener to the "Answer" button for mouseover event
    var answerButton = document.getElementById('q15');

    // Attach event listener to the "Answer" button for click event
    answerButton.addEventListener('click', hideq15);

    

            // Add each user reservation to the table
</script>
<!--Q3-->

    
    
             
    


    <!-- Copyright Section -->
    <div class="copyright">
        <p>&#169; COPYRIGHT AP RESORT.</p>
    </div>

    <script src="main.js"></script>
</body>