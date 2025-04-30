<?php  
session_start();

if (!isset($_SESSION['managers'])) {
    $_SESSION['managers'] = [
        ["id" => 1, "titre" => "Manager IT"],
        ["id" => 2, "titre" => "Manager RH"],
        ["id" => 3, "titre" => "Manager Financier"],
        ["id" => 4, "titre" => "Manager Communication"],
    ];
}

$managers = &$_SESSION['managers'];

$searchTitre = $_GET['searchTitre'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'titre';
$order = $_GET['order'] ?? 'asc';

$filteredManagers = array_filter($managers, function ($manager) use ($searchTitre) {
    return empty($searchTitre) || stripos($manager['titre'], $searchTitre) !== false;
});

usort($filteredManagers, function ($a, $b) use ($sortBy, $order) {
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
                <div class="top_admin">
                    <h1>Managers</h1>
                    <button class="initial"><a href="ajoutManager.php">Ajouter un manager</a></button>
                </div>
                <form method="GET">
                    <table class="table2">
                        <thead>
                            <tr class="grey_admin">
                                <th>
                                    <a href="?sortBy=titre&order=<?= $nextOrder ?>&searchTitre=<?= htmlspecialchars($searchTitre) ?>">
                                        Nom du Manager
                                        <span class="sort-arrow"><?= $sortBy === 'titre' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                                    </a>
                                    <input class="search_admin" type="text" name="searchTitre"
                                        value="<?= htmlspecialchars($searchTitre) ?>" placeholder="Rechercher..." />
                                </th>
                                <th>
                                    <button type="submit" class="search-btn">Rechercher</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($filteredManagers) > 0): ?>
                                <?php foreach ($filteredManagers as $manager): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($manager['titre']) ?></td>
                                        <td>
                                            <button class="det_button">
                                                <a href="manager_ajout.php?id=<?= urlencode($manager['id']) ?>">Détails</a>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="empty-row">Aucun manager trouvé</td>
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
