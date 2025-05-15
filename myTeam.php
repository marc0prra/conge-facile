<?php
session_start();
require 'config.php';

// Requête SQL
$sql = "
    SELECT 
        person.id,
        person.first_name,
        person.last_name,
        COALESCE(user.email, 'email introuvable') AS email,
        position.name AS role,
        department.name AS service
    FROM person
    LEFT JOIN user ON user.person_id = person.id
    LEFT JOIN department ON person.department_id = department.id
    LEFT JOIN position ON person.position_id = position.id
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
    <title>Liste du personnel</title>
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <div class="container_admin">
            <div class='top_admin'>
                <h1>Toutes les personnes</h1>
            </div>

            <table class="table2">
                <thead>
                    <tr class='grey_admin'>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Poste</th>
                        <th>Service</th>
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
                                <td><a href="team_details.php?id=<?= $p['id'] ?>" class="det_button">Détails</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="6" class="empty-row">Aucune personne trouvée</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
