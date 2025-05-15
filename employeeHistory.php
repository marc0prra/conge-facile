<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

include 'include/tabDonne.php';

include 'config.php';

$demandes = [];
$user_id = $_SESSION['user_id'];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=congefacile', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT 
                request.id,
                request.request_type_id,
                request_type.name AS type_demande, 
                request.created_at AS date_demande, 
                request.start_at AS date_debut, 
                request.end_at AS date_fin,
                request.answer AS etat_demande
              FROM request
              JOIN request_type ON request.request_type_id = request_type.id
              WHERE request.collaborator_id = :user_id
              ORDER BY request.created_at DESC ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css?v=2" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <title>Historique</title>
</head>

<body>
<?php include 'include/top.php'; ?>

<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right historique">
        <h1>Historique de mes demandes</h1>
        <div class="container">
            <table class="table1">
            <thead>
                <tr class='grey_Poste'>
                    <th class='searchHistorique sortable' data-column="0" data-type="text">
                        Type de demande <span class="arrow">▲</span><br>
                        <input id="search-type" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="1" data-type="date">
                        Demandée le <span class="arrow">▲</span><br>
                        <input id="search-demande" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="2" data-type="date">
                        Date de début <span class="arrow">▲</span><br>
                        <input id="search-debut" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="3" data-type="date">
                        Date de fin <span class="arrow">▲</span><br>
                        <input id="search-fin" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="4" data-type="number">
                        Nb de jours <span class="arrow">▲</span><br>
                        <input id="search-jours" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="5" data-type="text">
                        Statut <span class="arrow">▲</span><br>
                        <input id="search-statut" class='searchListe' type="text" />
                    </th>
                    <th></th>
                </tr>
            </thead>

                <tbody>
                    <?php if (count($demandes) > 0) : ?>
                        <?php foreach ($demandes as $demande) : ?>
                            <tr class="card">
                                <td class="Type1"><?= htmlspecialchars($demande['type_demande']) ?></td>
                                <td class="DemandeDate"><?= htmlspecialchars((new DateTime($demande['date_demande']))->format('d/m/Y H\hi')) ?></td>
                                <td class="DebutDate"><?= htmlspecialchars((new DateTime($demande['date_debut']))->format('d/m/Y H\hi')) ?></td>
                                <td class="FinDate"><?= htmlspecialchars((new DateTime($demande['date_fin']))->format('d/m/Y H\hi')) ?></td>
                                <td class="NbJours"><?= getWorkingDays($demande['date_debut'], $demande['date_fin'], $holidays); ?></td>
                                <td class="Statut"><?= getStatus($demande['etat_demande']) ?></td>
                                <td>
                                    <a class="det-button" href="leaveRequest.php?id=<?= $demande['id']; ?>">
                                        <button class="det-button">Détails</button>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="empty-row">Aucune demande trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="script.js"></script>
</body>
</html>