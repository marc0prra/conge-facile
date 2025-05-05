<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'] ?? null;
    $action = $_POST['action'] ?? null;
    $commentaire = $_POST['response_comment'] ?? '';

    if (!$requestId || !$action) {
        echo "Paramètres manquants.";
        exit();
    }

    // Déterminer la valeur de answer : 1 = accepté, 2 = refusé
    $answer = null;
    if ($action === 'accepter') {
        $answer = 1;
    } elseif ($action === 'refuser') {
        $answer = 2;
    } else {
        echo "Action non reconnue.";
        exit();
    }

    // Connexion à la base
    $pdo = new PDO('mysql:host=localhost;dbname=congefacile', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mise à jour
    $query = "UPDATE request SET answer = :answer, answer_comment = :comment WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':answer' => $answer,
        ':comment' => $commentaire,
        ':id' => $requestId
    ]);

    header("Location: demandInExpectation.php"); // Redirection après traitement
    exit();
} else {
    echo "Méthode non autorisée.";
    exit();
}
?>