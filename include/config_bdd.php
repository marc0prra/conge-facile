<?php

$host = "localhost";
$dbname = "congefacile";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}


if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}
