<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

include 'config.php';

$demandes = [];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=congefacile', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL modifiée pour filtrer en fonction des entrées de recherche
    $query = "SELECT 
                request.id,
                request.request_type_id,
                request_type.name AS type_demande, 
                request.created_at AS date_demande, 
                request.start_at AS date_debut, 
                request.end_at AS date_fin,
                request.answer AS etat_demande,
                person.first_name AS prenom,
                person.last_name AS nom
              FROM request
              JOIN request_type ON request.request_type_id = request_type.id
              JOIN person ON request.collaborator_id = person.id
              WHERE (request.answer = 1 OR request.answer = 2)";  // Filtrer pour les demandes acceptées ou refusées

    if ($searchType) {
        $query .= " AND request_type.name LIKE :searchType";
    }
    if ($searchDate) {
        $query .= " AND DATE(request.created_at) LIKE :searchDate";
    }
    if ($searchNb) {
        $query .= " AND DATEDIFF(request.end_at, request.start_at) = :searchNb";
    }

    $query .= " ORDER BY $sortBy $order";

    $stmt = $pdo->prepare($query);

    if ($searchType) {
        $stmt->bindValue(':searchType', "%$searchType%", PDO::PARAM_STR);
    }
    if ($searchDate) {
        $stmt->bindValue(':searchDate', "%$searchDate%", PDO::PARAM_STR);
    }
    if ($searchNb) {
        $stmt->bindValue(':searchNb', $searchNb, PDO::PARAM_INT);
    }

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
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <title>Historique</title>
</head>
<body>
<?php include 'include/top.php'; ?>

<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right historique">
        <h1>Historique des demandes</h1>
        <div class="container">
            <table class="table1">
            <thead>
                <tr class='grey_Poste'>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type_demande&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchDate=<?= htmlspecialchars($searchDate) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Type de demande
                            <span class="sort-arrow"><?= $sortBy === 'type_demande' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                        <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>" />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=prenom&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchDate=<?= htmlspecialchars($searchDate) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Collaborateur
                            <span class="sort-arrow"><?= $sortBy === 'prenom' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                        <input class='searchListe' type="text" name="searchCollaborateur" value="<?= htmlspecialchars($searchCollaborateur) ?>" />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=date_debut&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchDate=<?= htmlspecialchars($searchDate) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Date de début
                            <span class="sort-arrow"><?= $sortBy === 'date_debut' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                        <input class='searchListe' type="text" name="searchDate" value="<?= htmlspecialchars($searchDate) ?>" />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=date_fin&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchDate=<?= htmlspecialchars($searchDate) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Date de fin
                            <span class="sort-arrow"><?= $sortBy === 'date_fin' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                        <input class='searchListe' type="text" name="searchDate" value="<?= htmlspecialchars($searchDate) ?>" />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=nb_jours&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchDate=<?= htmlspecialchars($searchDate) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Nb de jours
                            <span class="sort-arrow"><?= $sortBy === 'nb_jours' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                        <input class='searchListe' type="text" name="searchNb" value="<?= htmlspecialchars($searchNb) ?>" />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=etat_demande&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchDate=<?= htmlspecialchars($searchDate) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Statut
                            <span class="sort-arrow"><?= $sortBy === 'etat_demande' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                        <input class='searchListe' type="text" name="searchStatut" value="<?= htmlspecialchars($searchStatut) ?>" />
                    </th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($demandes) > 0) : ?>
                    <?php foreach ($demandes as $demande) : ?>
                        <tr>
                            <td><?= htmlspecialchars($demande['type_demande']) ?></td>
                            <td><?= htmlspecialchars($demande['prenom']) ?> <?= htmlspecialchars($demande['nom']) ?></td>
                            <td><?= htmlspecialchars((new DateTime($demande['date_debut']))->format('d/m/Y H\hi')) ?></td>
                            <td><?= htmlspecialchars((new DateTime($demande['date_fin']))->format('d/m/Y H\hi')) ?></td>
                            <td><?= getWorkingDays($demande['date_debut'], $demande['date_fin'], $holidays); ?></td>
                            <td><?= getStatus($demande['etat_demande']) ?></td>
                            <td>
                                <a href="viewARequest.php?id=<?= $demande['id']; ?>">
                                    <button>Détails</button>
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
</body>
</html>


