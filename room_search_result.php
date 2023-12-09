<?php
// room_search_result.php

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

// Process room search
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user preferences from the form
    $room_category = $_POST["room_category"];
    $room_size = $_POST["room_size"];
    $airconditioner = $_POST["airconditioner"];
    $swimmingpool = $_POST["swimmingpool"];

    // Filter rooms based on user preferences
    $query = $db->prepare("
        SELECT room_name
        FROM roomlist
        WHERE room_category = ? AND room_size = ? AND airconditioner = ? AND swimmingpool = ?
        LIMIT 1
    ");
    $query->execute([$room_category, $room_size, $airconditioner, $swimmingpool]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Room is allotted, redirect to a confirmation page
        header("Location: room_allotted.php?room_name={$result['room_name']}");
        exit();
    } else {
        // No matching room found, redirect to a page indicating no availability
        header("Location: no_availability.php");
        exit();
    }
}
?>
