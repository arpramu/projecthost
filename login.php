<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = $db->prepare("SELECT * FROM admin WHERE username = :username");
    $query->bindParam(':username', $username);
    $query->execute();
    $admin = $query->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        // Start a session (if not already started)
        session_start();

        // Store admin information in session (you can store more information if needed)
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_username'] = $admin['username'];

        // Redirect to another page
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<p style='color: red;'>Invalid username or password.</p>";
    }
}
?>
