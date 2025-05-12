<?php  
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

require_once 'config.php'; // Connexion DB

$searchTitre = $_GET['searchTitre'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'name';
$order = ($_GET['order'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
$nextOrder = $order === 'asc' ? 'desc' : 'asc';

$allowedSort = ['name', 'nb_demandes'];
if (!in_array($sortBy, $allowedSort)) {
    $sortBy = 'name';
}

// Construction de la requête SQL
$sql = "
    SELECT rt.id, rt.name, COUNT(r.id) AS nb_demandes
    FROM request_type rt
    LEFT JOIN request r ON rt.id = r.request_type_id
    WHERE rt.name LIKE :search
    GROUP BY rt.id, rt.name
    ORDER BY $sortBy $order
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':search' => '%' . $searchTitre . '%']);
$demandes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Demandes</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <div class="container_admin">
            <div class="top_admin">
                <h1>Liste des Demandes</h1>
                <button class="initial"><a href="ajoutDemande.php">Ajouter une demande</a></button>
            </div>
            <form method="GET">
                <table class="table2">
                    <thead>
                        <tr class="grey_admin">
                            <th>
                                <a href="?sortBy=name&order=<?= $nextOrder ?>&searchTitre=<?= htmlspecialchars($searchTitre) ?>">
                                    Titre
                                    <span class="sort-arrow"><?= $sortBy === 'name' ? ($order === 'asc' ? '▲' : '▼') : '' ?></span>
                                </a>
                                <input class="search_admin" type="text" name="searchTitre" value="<?= htmlspecialchars($searchTitre) ?>" placeholder="Rechercher..." />
                            </th>
                            <th class="search_right_admin">
                                <a href="?sortBy=nb_demandes&order=<?= $nextOrder ?>&searchTitre=<?= htmlspecialchars($searchTitre) ?>">
                                    Nb demandes associées
                                    <span class="sort-arrow"><?= $sortBy === 'name' ? ($order === 'asc' ? '▲' : '▼') : '' ?></span>
                                </a>
                                <input class="search_admin" type="text" name="searchTitre" value="<?= htmlspecialchars($searchTitre) ?>" placeholder="Rechercher..." />
                            </th>
                            <th>
                                <button type="submit" class="search-btn">Rechercher</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($demandes) > 0): ?>
                            <?php foreach ($demandes as $demande): ?>
                                <tr>
                                    <td><?= htmlspecialchars($demande['name']) ?></td>
                                    <td><?= htmlspecialchars($demande['nb_demandes']) ?></td>
                                    <td>
                                        <button class="det_button">
                                            <a href="demande_ajout.php?id=<?= urlencode($demande['id']) ?>">Détails</a>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="empty-row">Aucune demande trouvée.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
</body>
</html>
