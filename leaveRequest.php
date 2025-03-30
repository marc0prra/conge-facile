<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];
$demande = null;

// Vérifier si un ID de demande est fourni
if (isset($_GET['id'])) {
    $demande_id = (int) $_GET['id'];
    
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=congefacile', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT request.id, request_type.name AS type_demande, 
                         request.start_at AS date_debut, request.end_at AS date_fin, 
                         request.manager_comment AS commentaire
                  FROM request
                  JOIN request_type ON request.request_type_id = request_type.id
                  WHERE request.id = :demande_id AND request.person_id = :user_id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':demande_id', $demande_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $demande = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="Style.css" />
    <title> Demande de congé </title>
</head>
<body>
<div class="borderTop"></div>
<div class="top">
    <img src="img/mentalworks.png" alt="Logo MentalWorks" />
</div>
<div class="middle">
    <div class="left">
        <a href="accueil.php">Accueil</a>
        <a href="nouvelle.php">Nouvelle demande</a>
        <a href="historique.php" class="active">Historique des demandes</a>
        <div class="rod"></div>
        <a href="">Mes informations</a>
        <a href="">Mes préférences</a>
        <a href="deconnexion.php">Déconnexion</a>
    </div>
    <div class="right">
        <h1> Ma demande de congé </h1>
        
        <?php if ($demande): ?>
            <p> Demande du <?= htmlspecialchars($demande['date_demande']) ?></p>
            <div class="sectionRequestDetails">
                <p class="TypeRequest"> Type de congé : <?= htmlspecialchars($demande['type_demande']) ?> </p>
                <p class="TypeRequest"> Période : <?= htmlspecialchars($demande['date_debut']) ?> au <?= htmlspecialchars($demande['date_fin']) ?> </p>
                <div class="justificative RequestDetails">
                    <p class="color">Commentaire du manager</p>
                    <input class="managerComment" type="text" name="comment" value="<?= htmlspecialchars($demande['commentaire'] ?? 'Aucun commentaire') ?>" readonly>
                </div>
                <a href="historique.php" class="moreDetails">Retourner à la liste de mes demandes</a>
            </div>
        <?php else: ?>
            <p>Aucune demande trouvée.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>