<?php
$host = "localhost";
$dbname = "congefacile";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

try {
    $pdo = new PDO("mysql:host=localhost;dbname=congefacile;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}


if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
} 

?>