<?php
// Include your PostgreSQL connection code here
$host = 'localhost';
$dbname = 'project';
$user = 'postgres';
$password = 'postgres';

try {
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Example: Find users who made a reservation
    $query = $db->prepare("
        SELECT users.username, reservation.check_in_date, reservation.check_out_date
        FROM users
        JOIN reservation ON users.user_id = reservation.user_id
    ");
    $query->execute();

    $userReservations = $query->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>