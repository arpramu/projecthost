<!-- admin.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://thumbs.dreamstime.com/b/luxury-beach-resort-bungalow-near-endless-pool-over-sea-sunset-evening-tropical-island-summer-vacation-concept-39672543.jpg'); /* Replace with your image URL */
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
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
            background-color: rgba(0, 0, 0, 0.7);
        }

        .button-container {
            margin-top: 20px;
        }

        .table{
            overflow: auto;
        }

        h2{
            text-align : center;
        }

        a {
            text-decoration: none;
            color: white;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid white;
            padding: 10px;
        }
        
    </style>
</head>
<body>

<div class="admin-container">
    <h2>Admin Panel</h2>
    <p id="a"><a href="admin.php">Back</a>   /     <a href="ap_resort.html">Home</a> </p>
    <br>    <br>    <br>    <br>
    <div class="button-container">
        <a href="?action=roomlist">View Room List</a>
        <a href="?action=reservations">View Reservations</a>
        <a href="?action=users">View Users</a>
        <a href="?action=payment_methods">View Payment Methods</a>
        <a href="?action=ratings">View Ratings</a>
        <a href="?action=exceeded_payments_log">View payment trigger</a>
    </div>
    <div class="table">
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

    // Check if an action is specified
    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        // Perform action based on user selection
        switch ($action) {
            case 'roomlist':
                viewTable($db, 'roomlist');
                break;
            case 'reservations':
                viewTable($db, 'reservation');
                break;
            case 'users':
                viewTable($db, 'users');
                break;
            case 'payment_methods':
                $q = $db->query("SELECT user_id,payment_id,payment_method FROM payment_method");
                $result = $q->fetchAll(PDO::FETCH_ASSOC);
                if ($result) {
                    echo "<h3> Payment Table</h3>";
                    echo "<table>";
                    echo "<tr>";

            // Display column headers
                foreach ($result[0] as $column => $value) {
                    echo "<th>$column</th>";
                }

                echo "</tr>";

                // Display table data
                foreach ($result as $row) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>$value</td>";
                    }
                    echo "</tr>";
                }

                echo "</table>";
                }
                break;
            case 'ratings':
                viewTable($db, 'rating');
                break;
            case 'exceeded_payments_log':
                viewTable($db, 'exceeded_payments_log');
                break;
            default:
                echo "<p>Invalid action selected.</p>";
                break;
        }
    }

    // Function to view a table
    function viewTable($db, $tableName) {
        $query = $db->query("SELECT * FROM $tableName");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            echo "<h3>$tableName Table</h3>";
            echo "<table>";
            echo "<tr>";

            // Display column headers
            foreach ($result[0] as $column => $value) {
                echo "<th>$column</th>";
            }

            echo "</tr>";

            // Display table data
            foreach ($result as $row) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>$value</td>";
                }
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No data found in $tableName table.</p>";
        }
    }
    ?>
    </div>
</div>

</body>
</html>
