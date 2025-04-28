<?php  
session_start();


if (!isset($_SESSION['postes'])) {
    $_SESSION['postes'] = [
        ["id" => 1, "titre" => "Développeur Web", "description" => "Création de sites et applications web."],
        ["id" => 2, "titre" => "Administrateur Réseau", "description" => "Gestion des infrastructures réseau."],
        ["id" => 3, "titre" => "Analyste Sécurité", "description" => "Protection des systèmes informatiques."],
        ["id" => 4, "titre" => "Chef de Projet IT", "description" => "Gestion de projets technologiques."],
    ];
}

$postes = &$_SESSION['postes'];

$searchTitre = $_GET['searchTitre'] ?? '';
$searchDescription = $_GET['searchDescription'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'titre';
$order = $_GET['order'] ?? 'asc';

$filteredPostes = array_filter($postes, function ($poste) use ($searchTitre, $searchDescription) {
    return 
        (empty($searchTitre) || stripos($poste['titre'], $searchTitre) !== false) &&
        (empty($searchDescription) || stripos($poste['description'], $searchDescription) !== false);
});

usort($filteredPostes, function ($a, $b) use ($sortBy, $order) {
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
    <title>Gestion des Postes</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <?php include 'include/top.php'; ?>
    <div class="middle">
        <?php include 'include/left.php'; ?>
        <div class="right">
            <div class="container_admin">
                <div class='top_admin'>
                    <h1>Liste des Postes</h1>
                    <button class="initial"><a href="poste_ajout.php">Ajouter un poste</a></button>
                </div>
                <form method="GET">
                    <table class="table2">
                        <thead>
                            <tr class='grey_admin'>
                                <th>
                                    <a href="?sortBy=titre&order=<?= $nextOrder ?>&searchTitre=<?= htmlspecialchars($searchTitre) ?>&searchDescription=<?= htmlspecialchars($searchDescription) ?>">
                                        Titre du Poste
                                        <span class="sort-arrow"><?= $sortBy === 'titre' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                                    </a>
                                    <input class='search_admin' type="text" name="searchTitre"
                                        value="<?= htmlspecialchars($searchTitre) ?>" placeholder="Rechercher..." />
                                </th>
                                <th class='search_right_admin'>
                                    <a href="?sortBy=description&order=<?= $nextOrder ?>&searchTitre=<?= htmlspecialchars($searchTitre) ?>&searchDescription=<?= htmlspecialchars($searchDescription) ?>">
                                        Nb postes liés
                                        <span class="sort-arrow"><?= $sortBy === 'description' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                                    </a>
                                    <input class='searchNb_admin' type="text" name="searchDescription"
                                        value="<?= htmlspecialchars($searchDescription) ?>" placeholder="Rechercher..." />
                                </th>
                                <th>
                                    <button type="submit" class="search-btn">Rechercher</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($filteredPostes) > 0) : ?>
                                <?php foreach ($filteredPostes as $poste) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($poste['titre']) ?></td>
                                        <td><?= htmlspecialchars($poste['description']) ?></td>
                                        <td>
                                            <button class="det_button">
                                                <a href="poste_ajout.php?id=<?= urlencode($poste['id']) ?>">Détails</a>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="empty-row"> Aucun poste trouvé</td>
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