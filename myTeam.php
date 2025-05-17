<?php
session_start();
require 'config.php';

$sql = "
    SELECT 
        person.id,
        person.first_name,
        person.last_name,
        COALESCE(user.email, 'email introuvable') AS email,
        position.name AS role,
        department.name AS service,
        (
            SELECT COUNT(*) 
            FROM request 
            WHERE request.collaborator_id = person.id 
              AND request.answer = 1
        ) AS conges_pris
    FROM person
    LEFT JOIN user ON user.person_id = person.id
    LEFT JOIN department ON person.department_id = department.id
    LEFT JOIN position ON person.position_id = position.id
    WHERE user.role = 'employee'
";



$result = $conn->query($sql);
$personnes = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css?v=2" />
    <link rel="icon" href="img/MW_logo.png" type="image/png"> 

    <title>Mon équipe</title>
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <div class="container_admin">
            <div class='top_admin'>
                <h1>Mon équipe</h1>
                <button class="initial"><a href="addTeam.php">Ajouter un collaborateur</a></button>
            </div>

            <table class="table3">
            <thead>
                <tr class='grey_Poste'>
                    <th class='searchHistorique sortable' data-column="0" data-type="text">
                        Nom <span class="arrow">▲</span><br>
                        <input id="search-type" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="1" data-type="date">
                        Prénom <span class="arrow">▲</span><br>
                        <input id="search-demande" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="2" data-type="text">
                        Email <span class="arrow">▲</span><br>
                        <input id="search-statut" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="3" data-type="date">
                        Poste <span class="arrow">▲</span><br>
                        <input id="search-debut" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="4" data-type="date">
                        Direction/Service <span class="arrow">▲</span><br>
                        <input id="search-fin" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="5" data-type="number">
                        Nombre de jours <span class="arrow">▲</span><br>
                        <input id="search-jours" class='searchListe' type="text" />
                    </th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($personnes)) : ?>
                    <?php foreach ($personnes as $p) : ?>
                        <tr>
                            <td><?= htmlspecialchars($p['last_name']) ?></td>
                            <td><?= htmlspecialchars($p['first_name']) ?></td>
                            <td><?= htmlspecialchars($p['email']) ?></td>
                            <td><?= htmlspecialchars($p['role'] ?? 'Non défini') ?></td>
                            <td><?= htmlspecialchars($p['service'] ?? 'Non défini') ?></td>
                            <td><?= htmlspecialchars($p['conges_pris'] ?? 0) ?></td>
                            <td><a href="team_details.php?id=<?= $p['id'] ?>" class="det_button">Détails</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="7" class="empty-row">Aucune personne trouvée</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        </div>
    </div>
</div>
</body>
</html>
