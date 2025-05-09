<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$order = 'asc'; 
$order = $_GET['order'] ?? $order;

include 'config.php';

$demandes = [];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=congefacile', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL modifiée pour récupérer également le nom et prénom
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
              JOIN person ON request.collaborator_id = person.id  -- Joindre la table person pour récupérer le nom et prénom
              WHERE request.answer =2 OR request.answer =1 ";  // Filtrer pour les demandes en cours

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $searchType = $_GET['searchType'] ?? '';
    $searchDate = $_GET['searchDate'] ?? '';
    $sortBy = $_GET['sortBy'] ?? 'date_demande';
    $order = $_GET['order'] ?? 'asc';
    $sortBy = $_GET['sortBy'] ?? 'date_demande'; 

    $nextOrder = ($order === 'asc') ? 'desc' : 'asc';
    $searchNb = $_GET['searchNb'] ?? '';

    if (!empty($demandes) && isset($demandes[0][$sortBy])) {
        usort($demandes, function ($a, $b) use ($sortBy, $order) {
            return ($order === 'asc') ? $a[$sortBy] <=> $b[$sortBy] : $b[$sortBy] <=> $a[$sortBy];
        });
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$nextOrder = ($order === 'asc') ? 'desc' : 'asc';

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
        <h1> Historique des demandes </h1>
        <div class="container">    
            <table class="table1">
            <thead>
                <tr class='grey_Poste'>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Type de demande 
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>"  />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Collaborateur
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>"  />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Date de début
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>"/>
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Date de fin 
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>" />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Nb de jours
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>" />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Statut
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>"  />
                    </th>
                    <th></th>
                </tr>
            </thead>
            <?php 
            $holidays = [
                "2025-01-01",
                "2025-04-21",
                "2025-05-01",
                "2025-05-08",
                "2025-05-29",
                "2025-06-09",
                "2025-07-14",
                "2025-08-15",
                "2025-11-01",
                "2025-11-11",
                "2025-12-25"
            ];

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

            function getStatus($codes) {
                // Convertir la chaîne en tableau
                $numbers = explode(',', $codes);
            
                // Vérifier les numéros dans l'ordre de priorité
                if (in_array('0', $numbers)) {
                    return 'En cours';
                } elseif (in_array('1', $numbers)) {
                    return 'Acceptée';
                } elseif (in_array('2', $numbers)) {
                    return 'Refusée';
                } else {
                    return 'Statut inconnu';
                }
            }
            
            ?>
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
                                    <a class="det-button" href="viewARequest.php?id=<?= $demande['id']; ?>">
                                        <button class="det-button">Détails</button>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="empty-row">Aucune demande</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

