<?php
// db.php - Αρχείο σύνδεσης με τη βάση
$host = "localhost";
$port = 3306;
$username = "root";
$password = "Olympiakos7";
$dbname = 'helpdesk_db'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Ρυθμίσεις για να μας δείχνει τα λάθη (σημαντικό για debugging)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>