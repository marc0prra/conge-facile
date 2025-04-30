<?php  
session_start();

if (!isset($_SESSION['directions'])) {
    $_SESSION['directions'] = [
        ["id" => 1, "titre" => "Direction Informatique", "description"],
        ["id" => 2, "titre" => "Direction RH", "description"],
        ["id" => 3, "titre" => "Direction Financière", "description"],
        ["id" => 4, "titre" => "Direction Communication", "description"],
    ];
}

$directions = &$_SESSION['directions'];

$searchTitre = $_GET['searchTitre'] ?? '';
$searchDescription = $_GET['searchDescription'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'titre';
$order = $_GET['order'] ?? 'asc';

$filteredDirections = array_filter($directions, function ($direction) use ($searchTitre, $searchDescription) {
    return 
        (empty($searchTitre) || stripos($direction['titre'], $searchTitre) !== false) &&
        (empty($searchDescription) || stripos($direction['description'], $searchDescription) !== false);
});

usort($filteredDirections, function ($a, $b) use ($sortBy, $order) {
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
    <title>Gestion des Directions</title>
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
                    <h1>Directions/Services</h1>
                    <button class="initial"><a href="ajoutDirection.php">Ajouter une direction</a></button>
                </div>
                <form method="GET">
                    <table class="table2">
                        <thead>
                            <tr class='grey_admin'>
                                <th>
                                    <a href="?sortBy=titre&order=<?= $nextOrder ?>&searchTitre=<?= htmlspecialchars($searchTitre) ?>&searchDescription=<?= htmlspecialchars($searchDescription) ?>">
                                        Nom de la Direction
                                        <span class="sort-arrow"><?= $sortBy === 'titre' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                                    </a>
                                    <input class='search_admin' type="text" name="searchTitre"
                                        value="<?= htmlspecialchars($searchTitre) ?>" placeholder="Rechercher..." />
                                </th>
                                <th>
                                    <button type="submit" class="search-btn">Rechercher</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($filteredDirections) > 0) : ?>
                                <?php foreach ($filteredDirections as $direction) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($direction['titre']) ?></td>
                                        <td>
                                            <button class="det_button">
                                                <a href="direction_ajout.php?id=<?= urlencode($direction['id']) ?>">Détails</a>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="empty-row">Aucune direction trouvée</td>
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
