<?php
session_start();
require 'config.php'; // Assure-toi que $conn (mysqli) est initialisé ici

// Récupération des filtres et tri
$searchNom = $_GET['searchNom'] ?? '';
$searchPrenom = $_GET['searchPrenom'] ?? '';
$searchService = $_GET['searchService'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'last_name';
$order = $_GET['order'] ?? 'asc';
$nextOrder = ($order === 'asc') ? 'desc' : 'asc';

// Construction des conditions dynamiques
$conditions = ["person.manager_id IS NOT NULL"];
$params = [];
$types = "";

if (!empty($searchNom)) {
    $conditions[] = "person.last_name LIKE ?";
    $params[] = "%" . $searchNom . "%";
    $types .= "s";
}
if (!empty($searchPrenom)) {
    $conditions[] = "person.first_name LIKE ?";
    $params[] = "%" . $searchPrenom . "%";
    $types .= "s";
}
if (!empty($searchService)) {
    $conditions[] = "department.name LIKE ?";
    $params[] = "%" . $searchService . "%";
    $types .= "s";
}

// Construction de la requête SQL
$sql = "
    SELECT person.id, person.first_name, person.last_name, department.name AS service
    FROM person
    LEFT JOIN department ON person.department_id = department.id
    WHERE " . implode(" AND ", $conditions) . "
    ORDER BY $sortBy $order
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erreur de préparation : " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$managers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css?v=2" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <title>Gestion des managers</title>
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
                                <a href="?sortBy=last_name&order=<?= $nextOrder ?>&searchNom=<?= htmlspecialchars($searchNom) ?>&searchPrenom=<?= htmlspecialchars($searchPrenom) ?>&searchService=<?= htmlspecialchars($searchService) ?>">
                                    Nom
                                    <span class="sort-arrow"><?= $sortBy === 'last_name' ? ($order === 'asc' ? '▲' : '▼') : '' ?></span>
                                </a>
                                <input class='search_admin' type="text" name="searchNom" value="<?= htmlspecialchars($searchNom) ?>" placeholder="Rechercher..." />
                            </th>
                            <th>
                                <a href="?sortBy=first_name&order=<?= $nextOrder ?>&searchNom=<?= htmlspecialchars($searchNom) ?>&searchPrenom=<?= htmlspecialchars($searchPrenom) ?>&searchService=<?= htmlspecialchars($searchService) ?>">
                                    Prénom
                                    <span class="sort-arrow"><?= $sortBy === 'first_name' ? ($order === 'asc' ? '▲' : '▼') : '' ?></span>
                                </a>
                                <input class='search_admin' type="text" name="searchPrenom" value="<?= htmlspecialchars($searchPrenom) ?>" placeholder="Rechercher..." />
                            </th>
                            <th>
                                <a href="?sortBy=service&order=<?= $nextOrder ?>&searchNom=<?= htmlspecialchars($searchNom) ?>&searchPrenom=<?= htmlspecialchars($searchPrenom) ?>&searchService=<?= htmlspecialchars($searchService) ?>">
                                    Service
                                    <span class="sort-arrow"><?= $sortBy === 'service' ? ($order === 'asc' ? '▲' : '▼') : '' ?></span>
                                </a>
                                <input class='search_admin' type="text" name="searchService" value="<?= htmlspecialchars($searchService) ?>" placeholder="Rechercher..." />
                            </th>
                            <th><button type="submit" class="search-btn">Rechercher</button></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($managers)) : ?>
                            <?php foreach ($managers as $m) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($m['last_name']) ?></td>
                                    <td><?= htmlspecialchars($m['first_name']) ?></td>
                                    <td><?= htmlspecialchars($m['service']) ?></td>
                                    <td><a href="manager_details.php?id=<?= $m['id'] ?>" class="det_button">Détails</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="4" class="empty-row">Aucun manager trouvé</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
</body>
</html>
