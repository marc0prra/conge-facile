<?php
// Tableau des types de demandes (simulation d'une base de données)
$typesDemandes = [
    ["type" => "Congé sans solde", "nb" => 400],
    ["type" => "Congé payé", "nb" => 1000],
    ["type" => "Congé maladie", "nb" => 750],
    ["type" => "Congé maternité/paternité", "nb" => 100],
    ["type" => "Autre", "nb" => 200],
];

// Récupération des paramètres GET (recherche et tri)
$searchType = $_GET['searchType'] ?? '';
$searchNb = $_GET['searchNb'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'type';
$order = $_GET['order'] ?? 'asc';

// 🔎 Filtrage
$filteredDemandes = array_filter($typesDemandes, function ($demande) use ($searchType, $searchNb) {
    return 
        (empty($searchType) || stripos($demande['type'], $searchType) !== false) &&
        (empty($searchNb) || $demande['nb'] == $searchNb);
});

// 🔀 Tri des résultats
usort($filteredDemandes, function ($a, $b) use ($sortBy, $order) {
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
    <link rel="stylesheet" href="style.css" />
    <title>Types de demandes</title>
</head>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

/ Conteneur /
.container {
    width: 90%;
    max-width: 1000px;
    margin-top: 20px;
}

h1 {
    color: #1d2d50;
}

/ Bouton ajouter /
.add-btn {
    background-color: #004b75;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-bottom: 20px;
}

.add-btn:hover {
    background-color: #003b5c;
}

/ Tableau /
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
}

td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

thead th a {
    text-decoration: none;
    color: #1d2d50;
}

thead th a:hover {
    text-decoration: underline;
}

.sort-arrow {
    margin-left: 5px;
    font-size: 12px;
    color: #a0aec0;
}

.details-btn {
    background-color: #e2e8f0;
    color: #1d2d50;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.details-btn:hover {
    background-color: #cbd5e1;
}

.search-btn {
    background-color: #004b75;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.search-btn:hover {
    background-color: #003b5c;
}

input[type="text"],
input[type="number"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    box-sizing: border-box;
}

Ligne vide
.empty-row {
    text-align: center;
    color: #a0aec0;
    font-style: italic;
}
</style>
<body>

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
</html>