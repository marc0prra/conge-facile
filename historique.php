<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die("Utilisateur non connecté. <a href='connexion.php'>Se connecter</a>");
}

include 'config.php';

$user_id = $_SESSION['user_id'];

try {
    // Connexion à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=congefacile', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparation et exécution de la requête
    $query = "SELECT 
                request_type.name AS type_demande, 
                request.created_at AS date_demande, 
                request.start_at AS date_debut, 
                request.end_at AS date_fin
              FROM request
              JOIN request_type ON request.request_type_id = request_type.id";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Affichage des résultats
    foreach ($demandes as $demande) {
        echo "Type de demande : " . htmlspecialchars($demande['type_demande']) . "<br>";
        echo "Date de la demande : " . htmlspecialchars($demande['date_demande']) . "<br>";
        echo "Date de début : " . htmlspecialchars($demande['date_debut']) . "<br>";
        echo "Date de fin : " . htmlspecialchars($demande['date_fin']) . "<br>";
        echo "------------------------------<br>";
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

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
      rel="stylesheet"
    />

    <link
      href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
      rel="stylesheet"
    />

    <title>Historique</title>
  </head>

<body>
  <div class="borderTop"></div>
  <div class="top">
    <img src="img/mentalworks.png" alt="Logo MentalWorks" />
  </div>
  <div class="middle">
    <div class="left">
      <a href="accueil.html">Accueil</a>
      <a href="nouvelle.html">Nouvelle demande</a>
      <a href="historique.html" class="active"> Historique des demandes</a>
      <div class="rod"></div>
      <a href="">Mes informations</a>
      <a href="">Mes préférences</a>
      <a href="">Déconnexion</a>
    </div>
    <div class="right">
        <h1>Historique de mes demandes</h1>
  <div class="container Historique">
    <?php
      $pdo = new PDO('mysql:host=localhost;dbname=congefacile', 'root', '');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $query = "SELECT request.*, request_type.name AS type_name, person.first_name, person.last_name, department.name AS department_name, position.name AS position_name
                FROM request
                JOIN request_type ON request.request_type_id = request_type.id
                JOIN person ON request.collaborator_id = person.id
                JOIN department ON person.department_id = department.id
                JOIN position ON person.position_id = position.id";

      $stmt = $pdo->prepare($query);
      $stmt->execute();
      $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="container">    
        <table>
            <thead>
                <tr class='grey_Poste'>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Type de demande 
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>"  />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Demandée le 
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>"  />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Date de début
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>"/>
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Date de fin 
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>" />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=type&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Date de fin 
                            <span class="sort-arrow"><?= $sortBy === 'type' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                            <input class='searchListe' type="text" name="searchType" value="<?= htmlspecialchars($searchType) ?>" />
                    </th>
                    <th class='searchHistorique'>
                        <a href="?sortBy=nb&order=<?= $nextOrder ?>&searchType=<?= htmlspecialchars($searchType) ?>&searchNb=<?= htmlspecialchars($searchNb) ?>">
                            Statut
                            <span class="sort-arrow"><?= $sortBy === 'nb' ? ($order === 'asc' ? '▲' : '▼') : '▼' ?></span>
                        </a>
                        <input class='searchListe'type="number" name="searchNb" value="<?= htmlspecialchars($searchNb) ?>" />
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <div class='tab_Poste'>
                    <?php if (count($filteredPoste) > 0) : ?>
                        <?php foreach ($filteredPoste as $Poste) : ?>
                            <tr>
                                <td><?= htmlspecialchars($demande['type_demande']) ?></td>
                                <td><?= htmlspecialchars($demande['date_demande']) ?></td>
                                <td><?= htmlspecialchars($demande['date_debut']) ?></td>
                                <td><?= htmlspecialchars($demande['date_fin']) ?></td>
                                <td><?= htmlspecialchars($demande['type_demande']) ?></td>
                                <td><?= htmlspecialchars($demande['type_demande']) ?></td>
                                <td>
                                    <button class="det_button">Détails</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="empty-row">Aucune demande trouvé</td>
                                </tr>
                    <?php endif; ?>
                </div>
            </tbody>
        </table>
    </div>

</body>
</html>
