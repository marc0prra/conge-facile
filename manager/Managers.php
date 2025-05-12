<?php
session_start();

if (!isset($_SESSION['managers'])) {
    $_SESSION['managers'] = [
        ["id" => 1, "nom" => "Dupont", "prenom" => "Jean", "service" => "Informatique"],
        ["id" => 2, "nom" => "Martin", "prenom" => "Sophie", "service" => "RH"],
        ["id" => 3, "nom" => "Durand", "prenom" => "Paul", "service" => "Finances"]
    ];
}

// Sécuriser les clés (au cas où des managers incomplets auraient été ajoutés)
foreach ($_SESSION['managers'] as &$m) {
    $m['nom'] = $m['nom'] ?? '';
    $m['prenom'] = $m['prenom'] ?? '';
    $m['service'] = $m['service'] ?? '';
}
unset($m); // Bonnes pratiques pour éviter des erreurs avec la variable de référence

$managers = &$_SESSION['managers'];

// Filtres et tri
$searchNom = $_GET['searchNom'] ?? '';
$searchPrenom = $_GET['searchPrenom'] ?? '';
$searchService = $_GET['searchService'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'nom';
$order = $_GET['order'] ?? 'asc';

// Filtrage
$filteredManagers = array_filter($managers, function ($m) use ($searchNom, $searchPrenom, $searchService) {
    return 
        (empty($searchNom) || stripos($m['nom'], $searchNom) !== false) &&
        (empty($searchPrenom) || stripos($m['prenom'], $searchPrenom) !== false) &&
        (empty($searchService) || stripos($m['service'], $searchService) !== false);
});

// Tri
usort($filteredManagers, function ($a, $b) use ($sortBy, $order) {
    if (!isset($a[$sortBy]) || !isset($b[$sortBy])) return 0;
    return $order === 'asc' ? $a[$sortBy] <=> $b[$sortBy] : $b[$sortBy] <=> $a[$sortBy];
});

$nextOrder = ($order === 'asc') ? 'desc' : 'asc';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Managers</title>
    <link rel="stylesheet" href="style.css?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <div class="container_admin">
            <div class='top_admin'>
                <h1>Managers</h1>
                <button class="initial"><a href="addManager.php">Ajouter un manager</a></button>
            </div>
            <form method="GET">
                <table class="table2">
                    <thead>
                        <tr class='grey_admin'>
                            <th>
                                <a href="?sortBy=nom&order=<?= $nextOrder ?>&searchNom=<?= htmlspecialchars($searchNom) ?>&searchPrenom=<?= htmlspecialchars($searchPrenom) ?>&searchService=<?= htmlspecialchars($searchService) ?>">
                                    Nom
                                    <span class="sort-arrow"><?= $sortBy === 'nom' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                                </a>
                                <input class='search_admin' type="text" name="searchNom" value="<?= htmlspecialchars($searchNom) ?>" placeholder="Rechercher..." />
                            </th>
                            <th>
                                <a href="?sortBy=prenom&order=<?= $nextOrder ?>&searchNom=<?= htmlspecialchars($searchNom) ?>&searchPrenom=<?= htmlspecialchars($searchPrenom) ?>&searchService=<?= htmlspecialchars($searchService) ?>">
                                    Prénom
                                    <span class="sort-arrow"><?= $sortBy === 'prenom' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                                </a>
                                <input class='search_admin' type="text" name="searchPrenom" value="<?= htmlspecialchars($searchPrenom) ?>" placeholder="Rechercher..." />
                            </th>
                            <th>
                                <a href="?sortBy=service&order=<?= $nextOrder ?>&searchNom=<?= htmlspecialchars($searchNom) ?>&searchPrenom=<?= htmlspecialchars($searchPrenom) ?>&searchService=<?= htmlspecialchars($searchService) ?>">
                                    Service
                                    <span class="sort-arrow"><?= $sortBy === 'service' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                                </a>
                                <input class='search_admin' type="text" name="searchService" value="<?= htmlspecialchars($searchService) ?>" placeholder="Rechercher..." />
                            </th>
                            <th><button type="submit" class="search-btn">Rechercher</button></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($filteredManagers) > 0) : ?>
                            <?php foreach ($filteredManagers as $m) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($m['nom'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($m['prenom'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($m['service'] ?? '') ?></td>
                                    <td>
                                        <a href="manager_details.php?id=<?= urlencode($m['id']) ?>" class="det_button">Détails</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="empty-row">Aucun manager trouvé</td>
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
