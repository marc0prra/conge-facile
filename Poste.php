
<?php
// Tableau de Postes 
$typesPoste = [
    ["type" => "Développeur", "nb" => 13],
    ["type" => "Développeur applications mobiles", "nb" => 4],
    ["type" => "Développeur C#", "nb" => 3],
    ["type" => "Graphiste", "nb" => 2],
    ["type" => "Community Manager", "nb" => 1],
];

// Récupération GET (recherche et tri)
$searchType = $_GET['searchType'] ?? '';
$searchNb = $_GET['searchNb'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'type';
$order = $_GET['order'] ?? 'asc';

// Filtrage
$filteredPoste = array_filter($typesPoste, function ($Poste) use ($searchType, $searchNb) {
    return 
        (empty($searchType) || stripos($Poste['type'], $searchType) !== false) &&
        (empty($searchNb) || $Poste['nb'] == $searchNb);
});

// Tri 
usort($filteredPoste, function ($a, $b) use ($sortBy, $order) {
    if ($order === 'asc') {
        return $a[$sortBy] <=> $b[$sortBy];
    } else {
        return $b[$sortBy] <=> $a[$sortBy];
    }
});

// Inversion de l'ordre pour le prochain tri
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
    <link
      href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"/>

    <link
      href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
      rel="stylesheet"/>
      <title>Poste</title>

<body>
  <?php include 'top.php'?>
  <div class='middle'>
  <?php include 'left_admin.php'?>
    <div class="right">
      
<div class="container_Poste">
    <div class='top_Poste'>
        <h1>Postes</h1>
        <button class="add-btn">Ajouter un Poste</button>
    </div>
    
    <form method="GET">
        <table>
            <thead>
                <tr class='grey_Poste'>
                    <th>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Nom du Poste
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='search_Poste' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>" placeholder="Rechercher..." />
                    </th>
                    <th class='search_right_Poste'>
                        <a href="?sortBy=nb&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Nb personnes liées
                            <span class="sort-arrow"><?= $sortBy === 'nb' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchNb_Poste'type="number" name="searchNb" value="<?= htmlspecialchars($searchNb) ?>" placeholder="Rechercher..." />
                    </th>
                    <th>
                        <button type="submit" class="search-btn">Rechercher</button>

                    </th>
                </tr>
            </thead>
            <tbody>
                <div class='tab_Poste'>
                    <?php if (count($filteredPoste) > 0) : ?>
                        <?php foreach ($filteredPoste as $Poste) : ?>
                            <tr>
                                <td><?= htmlspecialchars($Poste['type']) ?></td>
                                <td><?= htmlspecialchars($Poste['nb']) ?></td>
                                <td>
                                    <button class="det_button">Détails</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="empty-row">Aucun poste trouvé</td>
                                </tr>
                    <?php endif; ?>
                </div>
            </tbody>
        </table>
    </form>
    </div>
  </div>
  </body>
