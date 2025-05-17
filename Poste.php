<?php
require_once("include/config_bdd.php");
require_once("include/user.php");

$sql = "
    SELECT 
        p.id,
        p.name,
        COUNT(pe.id) AS nb_postes_dispo
    FROM position p
    LEFT JOIN person pe ON p.id = pe.position_id
    GROUP BY p.id, p.name
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$filteredPostes = $stmt->fetchAll();

if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] == 'manager')) {
    header("Location: connexion.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Postes</title>
    <link rel="stylesheet" href="style.css?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100..900&family=Inter:wght@100..900&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'include/top.php'; ?>

    <div class="middle">
        <?php include 'include/left.php'; ?>

        <div class="right">
            <div class="container_admin">
                <div class="top_admin">
                    <h1>Liste des Postes</h1>
                    <button class="initial"><a href="addPoste.php">Ajouter un poste</a></button>
                </div>
                <table class="table2">
                    <thead>
                        <tr class="grey_admin">
                            <th data-sort="title" data-order="asc">Titre du Poste ▲</th>
                            <th data-sort="count" data-order="asc">Nb personnes liées ▲</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th><input id="search-title" class="searchListe" type="text" placeholder="Rechercher…"></th>
                            <th><input id="search-count" class="searchListe" type="text" placeholder="Rechercher…"></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($filteredPostes): ?>
                            <?php foreach ($filteredPostes as $poste): ?>
                                <tr>
                                    <td data-col="title"><?= htmlspecialchars($poste['name']) ?></td>
                                    <td data-col="count"><?= htmlspecialchars($poste['nb_postes_dispo']) ?></td>
                                    <td>
                                        <button class="det_button">
                                            <a href="poste_details.php?id=<?= urlencode($poste['id']) ?>">Détails</a>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="empty-row">Aucun poste trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const titleInput = document.getElementById('search-title');
            const countInput = document.getElementById('search-count');
            const rows = [...document.querySelectorAll('tbody tr')];
            const headers = document.querySelectorAll('th[data-sort]');

            const filterRows = () => {
                const tVal = titleInput.value.trim().toLowerCase();
                const cVal = countInput.value.trim();
                rows.forEach(r => {
                    const tText = r.querySelector('[data-col="title"]').textContent.toLowerCase();
                    const cText = r.querySelector('[data-col="count"]').textContent.trim();
                    const okT = tText.includes(tVal);
                    const okC = cVal === '' || cText === cVal;
                    r.style.display = (okT && okC) ? '' : 'none';
                });
            };
            titleInput.addEventListener('input', filterRows);
            countInput.addEventListener('input', filterRows);

            const sortTable = (key, order) => {
                const factor = order === 'asc' ? 1 : -1;
                rows.sort((a, b) => {
                    const aVal = a.querySelector(`[data-col="${key}"]`).textContent.trim();
                    const bVal = b.querySelector(`[data-col="${key}"]`).textContent.trim();
                    if (key === 'count') {
                        return (Number(aVal) - Number(bVal)) * factor;
                    }
                    return aVal.localeCompare(bVal) * factor;
                });
                const tbody = document.querySelector('tbody');
                rows.forEach(r => tbody.appendChild(r));
            };

            headers.forEach(h => {
                h.addEventListener('click', () => {
                    const key = h.dataset.sort;
                    const currentOrder = h.dataset.order;
                    const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';

                    headers.forEach(el => {
                        el.textContent = el.textContent.replace('▲', '').replace('▼', '').trim() + ' ▲';
                        el.dataset.order = 'asc';
                    });

                    h.dataset.order = newOrder;
                    h.textContent = h.textContent.replace('▲', '').replace('▼', '').trim() + (newOrder === 'asc' ? ' ▲' : ' ▼');

                    sortTable(key, newOrder);
                    filterRows();
                });
            });
        });
    </script>

</body>

</html>