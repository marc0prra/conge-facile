<?php
require 'config.php';

$searchTitre = $_GET['searchTitre'] ?? '';
$searchDescription = $_GET['searchDescription'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'id';
$order = $_GET['order'] ?? 'asc';
$nextOrder = ($order === 'asc') ? 'desc' : 'asc';

// Construction de la requête
$sql = "SELECT * FROM position";
$params = [];



$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$filteredPostes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Postes</title>
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
                    <h1>Liste des Postes</h1>
                    <button class="initial"><a href="addPoste.php">Ajouter un poste</a></button>
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
                                    <input class='searchNb_admin' type="number" name="searchDescription"
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
                                        <td><?= htmlspecialchars($poste['name']) ?></td>
                                        <td><?= htmlspecialchars($poste['nb_postes_dispo']) ?></td>
                                        <td>
                                            <button class="det_button">
                                                <a href="poste_details.php?id=<?= urlencode($poste['id']) ?>">Détails</a>
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