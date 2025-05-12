<?php
session_start();

include 'include/tabDonne'

include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=congefacile', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$request_id = $_GET['id'] ?? null;

if (!$request_id) {
    echo "ID de demande manquant.";
    exit();
}


$query = "SELECT 
            request.id,
            request.request_type_id,
            request_type.name AS type_demande, 
            request.created_at AS date_demande, 
            request.start_at AS date_debut, 
            request.end_at AS date_fin,
            request.comment AS commentaire,
            request.answer AS etat_demande
          FROM request
          JOIN request_type ON request.request_type_id = request_type.id
          WHERE request.id = :request_id";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':request_id', $request_id, PDO::PARAM_INT);
$stmt->execute();
$demande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$demande) {
    echo "Aucune demande trouvée.";
    exit();
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
        <?php include 'include/top.php'; ?>
        <div class="middle">
            <?php include 'include/left.php'; ?>
            <div class="right">
                <h1 class="titleDemand"> Ma demande de congé </h1>

                <?php if ($demande): ?>
                <p class="subTiltleDemand"> Demande du <?=htmlspecialchars(date('d/m/Y', strtotime($demande['date_demande']))) ?></p>
                <div class="sectionRequestDetails">
                    <p class="TypeRequest"> Type de congé : <?= htmlspecialchars($demande['type_demande']) ?> </p>
                    <p class="TypeRequest"> Période : <?= htmlspecialchars((new DateTime($demande['date_debut']))->format('d/m/Y H\h00')) ?> au <?= htmlspecialchars((new DateTime($demande['date_fin']))->format('d/m/Y H\h00')) ?> </p>
                    <p class="TypeRequest"> Nombre de jours : <?= getWorkingDays($demande['date_debut'], $demande['date_fin'], $holidays); ?></p>
                    <div class="justificative RequestDetails">
                        <p class="color">Commentaire du manager</p>
                        <input class="managerComment" type="text" name="comment"
                            value="<?= htmlspecialchars($demande['commentaire'] ?? 'Aucun commentaire') ?>" readonly>
                    </div>
                    <a href="employeeHistory.php" class="moreDetails">Retourner à la liste de mes demandes</a>
                </div>
                <?php else: ?>
                <p>Aucune demande trouvée.</p>
                <?php endif; ?>
            </div>
        </div>
    </body>

</html>