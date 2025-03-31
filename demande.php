<?php  
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}


if (!isset($_SESSION['demandes'])) {
    $_SESSION['demandes'] = [
        ["id" => 1, "titre" => "Demande de congé", "description" => "Demande de congé annuel."],
        ["id" => 2, "titre" => "Demande de matériel", "description" => "Commande de nouveau matériel informatique."],
        ["id" => 3, "titre" => "Demande de formation", "description" => "Inscription à une formation professionnelle."],
        ["id" => 4, "titre" => "Demande de remboursement", "description" => "Remboursement des frais professionnels."],
    ];
}

$demandes = &$_SESSION['demandes'];

$searchTitre = $_GET['searchTitre'] ?? '';
$searchDescription = $_GET['searchDescription'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'titre';
$order = $_GET['order'] ?? 'asc';

$filteredDemandes = array_filter($demandes, function ($demande) use ($searchTitre, $searchDescription) {
    return 
        (empty($searchTitre) || stripos($demande['titre'], $searchTitre) !== false) &&
        (empty($searchDescription) || stripos($demande['description'], $searchDescription) !== false);
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
        <title>Gestion des Demandes</title>
        <link rel="stylesheet" href="style.css?v=2">
    </head>

    <body>
        <?php include 'include/top.php'; ?>
        <div class="middle">
            <?php include 'include/left.php'; ?>
            <div class="right">
                <div class="container_admin">
                    <div class='top_admin'>
                        <h1>Liste des Demandes</h1>
                        <button class="initial"><a href="demande_ajout.php">Ajouter une demande</a></button>
                    </div>
                    <form method="GET">
                        <table class="table2">
                            <thead>
                                <tr class='grey_admin'>
                                    <th>
                                        <a
                                            href="?sortBy=titre&order=<?= $nextOrder ?>&searchTitre=<?= htmlspecialchars($searchTitre) ?>&searchDescription=<?= htmlspecialchars($searchDescription) ?>">
                                            Titre de la Demande
                                            <span
                                                class="sort-arrow"><?= $sortBy === 'titre' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                                        </a>
                                        <input class='search_admin' type="text" name="searchTitre"
                                            value="<?= htmlspecialchars($searchTitre) ?>" placeholder="Rechercher..." />
                                    </th>
                                    <th class='search_right_admin'>
                                        <a
                                            href="?sortBy=description&order=<?= $nextOrder ?>&searchTitre=<?= htmlspecialchars($searchTitre) ?>&searchDescription=<?= htmlspecialchars($searchDescription) ?>">
                                            Nb demandes associés
                                            <span
                                                class="sort-arrow"><?= $sortBy === 'description' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                                        </a>
                                        <input class='searchNb_admin' type="text" name="searchDescription"
                                            value="<?= htmlspecialchars($searchDescription) ?>"
                                            placeholder="Rechercher..." />
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
                                    <td><?= htmlspecialchars($demande['titre']) ?></td>
                                    <td><?= htmlspecialchars($demande['description']) ?></td>
                                    <td>
                                        <button class="det_button">
                                            <a href="demande_ajout.php?id=<?= urlencode($demande['id']) ?>">Détails</a>
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