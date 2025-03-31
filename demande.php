<?php 
session_start();
$typesDemandes = [
    ["type" => "Congé sans solde", "nb" => 400],
    ["type" => "Congé payé", "nb" => 1000],
    ["type" => "Congé maladie", "nb" => 750],
    ["type" => "Congé maternité/paternité", "nb" => 100],
    ["type" => "Autre", "nb" => 200],
];

$searchType = $_GET['searchType'] ?? '';
$searchNb = $_GET['searchNb'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'type';
$order = $_GET['order'] ?? 'asc';

$filteredDemandes = array_filter($typesDemandes, function ($demande) use ($searchType, $searchNb) {
    return 
        (empty($searchType) || stripos($demande['type'], $searchType) !== false) &&
        (empty($searchNb) || $demande['nb'] == $searchNb);
});

usort($filteredDemandes, function ($a, $b) use ($sortBy, $order) {
    if ($order === 'asc') {
        return $a[$sortBy] <=> $b[$sortBy];
    } else {
        return $b[$sortBy] <=> $a[$sortBy];
    }
});

$nextOrder = ($order === 'asc') ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=2">
    <title>Types de demandes</title>
</head>

<body>
    <?php include 'include/top.php'; ?>
    <div class="middle">
        <?php include 'include/left.php'; ?>
        <div class="right">
            <div class="container_admin">
                <div class='top_admin'>
                    <h1>Types de demandes</h1>
                    <button class="initial"><a href="demande_ajout.php">Ajouter un type de demande</a></button>
                </div>

                <form method="GET">
                    <table class="table2">
                        <thead>
                            <tr class='grey_admin'>
                                <th>
                                    <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                                        Nom du type de demande
                                        <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                                    </a>
                                    <input class='search_admin' type="text" name="searchType"
                                        value="<?= htmlspecialchars($searchType) ?>" placeholder="Rechercher..." />
                                </th>
                                <th class='search_right_admin'>
                                    <a href="?sortBy=nb&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                                        Nb demandes associées
                                        <span class="sort-arrow"><?= $sortBy === 'nb' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                                    </a>
                                    <input class='searchNb_admin' type="number" name="searchNb"
                                        value="<?= htmlspecialchars($searchNb) ?>" placeholder="Rechercher..." />
                                </th>
                                <th>
                                    <button type="submit" class="search-btn">Rechercher</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($filteredDemandes) > 0) : ?>
                                <?php foreach ($filteredDemandes as $demande) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($demande['type']) ?></td>
                                        <td><?= htmlspecialchars($demande['nb']) ?></td>
                                        <td>
                                            <button class="det_button">
                                                <a href="demande_ajout.php?type=<?= urlencode($demande['type']) ?>">Détails</a>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="empty-row"> Aucune demande trouvée</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
