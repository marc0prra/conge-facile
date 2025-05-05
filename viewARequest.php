<?php
session_start();
include 'config.php';

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=congefacile', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupération de l'ID de la demande
$request_id = $_GET['id'] ?? null;

if (!$request_id) {
    echo "ID de demande manquant.";
    exit();
}

// Requête de récupération des informations de la demande
$query = "
    SELECT 
        request.id,
        request.request_type_id,
        request_type.name AS type_demande,
        request.created_at AS date_demande,
        request.start_at AS date_debut,
        request.end_at AS date_fin,
        request.comment AS commentaire,
        request.receipt_file,
        person.first_name AS prenom,
        person.last_name AS nom
    FROM request
    JOIN request_type ON request.request_type_id = request_type.id
    JOIN person ON request.collaborator_id = person.id
    WHERE request.id = :request_id
";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':request_id', $request_id, PDO::PARAM_INT);
$stmt->execute();
$demande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$demande) {
    echo "Aucune demande trouvée.";
    exit();
}

// Jours fériés
$holidays = [
    "2025-01-01", "2025-04-21", "2025-05-01", "2025-05-08",
    "2025-05-29", "2025-06-09", "2025-07-14", "2025-08-15",
    "2025-11-01", "2025-11-11", "2025-12-25"
];

// Fonction de calcul des jours ouvrés
function getWorkingDays($start, $end, $holidays = []) {
    $begin = new DateTime($start);
    $end = new DateTime($end);
    $end->modify('+1 day');

    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($begin, $interval, $end);

    $workingDays = 0;
    foreach ($dateRange as $date) {
        $day = $date->format('N'); // 6 = samedi, 7 = dimanche
        $formatted = $date->format('Y-m-d');
        if ($day < 6 && !in_array($formatted, $holidays)) {
            $workingDays++;
        }
    }
    return $workingDays;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consulter une demande</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue&family=Inter&display=swap" rel="stylesheet">

    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
<?php include 'include/top.php'; ?>

<div class="middle">
    <?php include 'include/left.php'; ?>

    <div class="right">
        <h1>Demande de <?= htmlspecialchars($demande['prenom']) ?> <?= htmlspecialchars($demande['nom']) ?></h1>
        <p>Demande créée le <?= htmlspecialchars(date('d/m/Y', strtotime($demande['date_demande']))) ?></p>

        <div class="sectionRequestDetails">
            <p>
                Du <?= htmlspecialchars((new DateTime($demande['date_debut']))->format('d/m/Y H\h00')) ?>
                au <?= htmlspecialchars((new DateTime($demande['date_fin']))->format('d/m/Y H\h00')) ?>
            </p>

            <p class="TypeRequest">Type de congé : <?= htmlspecialchars($demande['type_demande']) ?></p>

            <p class="TypeRequest">
                Nombre de jours : <?= getWorkingDays($demande['date_debut'], $demande['date_fin'], $holidays) ?>
            </p>

            <div class="justificative RequestDetails">
                <p class="color">Commentaire supplémentaire</p>
                <input class="managerComment" type="text" name="comment" readonly
                       value="<?= htmlspecialchars($demande['commentaire'] ?? 'Aucun commentaire') ?>">
            </div>

            <?php if (!empty($demande['receipt_file'])) : ?>
                <a href="uploads/<?= htmlspecialchars($demande['receipt_file']) ?>" class="moreDetails" download>
                    Télécharger le justificatif
                </a>
            <?php else : ?>
                <p>Aucun justificatif disponible.</p>
            <?php endif; ?>

            <div class="managerResponse">
                <h1>Répondre à la demande</h1>

                <form method="post" action="traitementReponse.php">
                    <div class="justificative RequestDetails">
                        <p class="color">Saisir un commentaire</p>
                        <input class="managerComment" type="text" name="response_comment"
                               placeholder="Votre commentaire ici">

                        <div class="end">
                            <button class="reject" type="submit" name="action" value="refuser">Refuser la demande </button>
                            <button class="accept" type="submit" name="action" value="accepter">Valider la demande</button>
                        </div>
                    </div>

                    <input type="hidden" name="request_id" value="<?= htmlspecialchars($demande['id']) ?>">
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>