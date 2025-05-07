<?php  
require 'config.php'; // connexion à la base

$searchTitre = $_GET['searchTitre'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'name';
$order = $_GET['order'] ?? 'asc';

// Sécurité : uniquement autoriser certains champs pour le tri
$allowedSortFields = ['name'];
$sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'name';
$order = strtolower($order) === 'desc' ? 'DESC' : 'ASC';

$sql = "SELECT * FROM services WHERE name LIKE :search ORDER BY $sortBy $order";
$stmt = $pdo->prepare($sql);
$searchParam = "%$searchTitre%";
$stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

$nextOrder = ($order === 'ASC') ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services</title>
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
                    <h1>Liste des Services</h1>
                    <button class="initial"><a href="ajoutDirection.php">Ajouter un service</a></button>
                </div>
                <form method="GET">
                    <table class="table2">
                        <thead>
                            <tr class='grey_admin'>
                                <th>
                                    <a href="?sortBy=name&order=<?= $nextOrder ?>&searchTitre=<?= htmlspecialchars($searchTitre) ?>">
                                        Nom du service
                                        <span class="sort-arrow"><?= $sortBy === 'name' ? ($order === 'ASC' ? '▲' : '▼') : '▼' ?></span>
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
                            <?php if (count($services) > 0) : ?>
                                <?php foreach ($services as $service) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($service['name']) ?></td>
                                        <td>
                                            <button class="det_button">
                                                <a href="direction_ajout.php?id=<?= urlencode($service['id']) ?>">Détails</a>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="2" class="empty-row">Aucun service trouvé</td>
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
