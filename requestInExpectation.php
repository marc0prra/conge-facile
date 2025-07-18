<?php

require_once("include/config_bdd.php");
require_once("include/user.php");

include 'include/tabDonne.php';


$demandes = [];

try {

    $stmt = $pdo->prepare(
        "SELECT person_id
           FROM user
          WHERE id = :user_id"
    );
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $managerPersonId = $stmt->fetchColumn();

    if (!$managerPersonId) {
        $demandes = [];
    } else {

        $sql = "
            SELECT  r.id,
                    r.request_type_id,
                    rt.name      AS type_demande,
                    r.created_at AS date_demande,
                    r.start_at   AS date_debut,
                    r.end_at     AS date_fin,
                    r.answer     AS etat_demande,
                    p.first_name AS prenom,
                    p.last_name  AS nom
            FROM request r
            JOIN request_type rt ON r.request_type_id = rt.id
            JOIN person p        ON r.collaborator_id = p.id
            WHERE r.answer = 0
              AND p.manager_id = :manager_id
            ORDER BY r.created_at DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':manager_id' => $managerPersonId]);
        $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] == 'manager')) {
    header("Location: connexion.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="img/MW_logo.png" type="image/png">

    <link rel="stylesheet" href="style.css?v=2">

    <link rel="icon" href="img/MW_logo.png" type="image/png">

    <title>Historique</title>
</head>


<body>
    <?php include 'include/top.php'; ?>
    <div class="middle">
        <?php include 'include/left.php'; ?>
        <div class="right historique">
            <h1>Historique des demandes</h1>
            <div class="container">
                <table class="table1">
                    <thead>
                        <tr class='grey_Poste'>
                            <th class='searchHistorique sortable' data-column="0" data-type="text">
                                Type de demande <span class="arrow">▲</span><br>
                                <input id="search-type" class='searchListe' type="text" />
                            </th>
                            <th class='searchHistorique sortable' data-column="1" data-type="date">
                                Demandée le <span class="arrow">▲</span><br>
                                <input id="search-demande" class='searchListe' type="text" />
                            </th>
                            <th class='searchHistorique sortable' data-column="2" data-type="text">
                                Collaborateur <span class="arrow">▲</span><br>
                                <input id="search-statut" class='searchListe' type="text" />
                            </th>
                            <th class='searchHistorique sortable' data-column="3" data-type="date">
                                Date de début <span class="arrow">▲</span><br>
                                <input id="search-debut" class='searchListe' type="text" />
                            </th>
                            <th class='searchHistorique sortable' data-column="4" data-type="date">
                                Date de fin <span class="arrow">▲</span><br>
                                <input id="search-fin" class='searchListe' type="text" />
                            </th>
                            <th class='searchHistorique sortable' data-column="5" data-type="number">
                                Nb de jours <span class="arrow">▲</span><br>
                                <input id="search-jours" class='searchListe' type="text" />
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="demande-body">
                        <?php foreach ($demandes as $demande): ?>
                            <tr class="card">
                                <td class="type_demande"><?= htmlspecialchars($demande['type_demande']) ?></td>
                                <td class="demande">
                                    <?= htmlspecialchars((new DateTime($demande['date_demande']))->format('d/m/Y H\hi')) ?>
                                </td>
                                <td class="collab"><?= htmlspecialchars($demande['prenom']) ?>
                                    <?= htmlspecialchars($demande['nom']) ?>
                                </td>
                                <td class="date_debut">
                                    <?= htmlspecialchars((new DateTime($demande['date_debut']))->format('d/m/Y H\hi')) ?>
                                </td>
                                <td class="date_fin">
                                    <?= htmlspecialchars((new DateTime($demande['date_fin']))->format('d/m/Y H\hi')) ?>
                                </td>
                                <td class="jours">
                                    <?= getWorkingDays($demande['date_debut'], $demande['date_fin'], $holidays); ?>
                                </td>
                                <td>
                                    <a class="det-button" href="viewARequest.php?id=<?= $demande['id']; ?>">
                                        <button class="det-button">Détails</button>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const filters = {
            type: document.querySelector("#search-type"),
            demande: document.querySelector("#search-demande"),
            collab: document.querySelector("#search-statut"),
            debut: document.querySelector("#search-debut"),
            fin: document.querySelector("#search-fin"),
            jours: document.querySelector("#search-jours")
        };

        Object.values(filters).forEach(input => {
            input.addEventListener("keyup", filterRows);
        });

        function filterRows() {
            const rows = document.querySelectorAll(".card");

            rows.forEach(row => {
                const match = (
                    row.querySelector(".type_demande").textContent.toLowerCase().includes(filters.type.value.toLowerCase()) &&
                    row.querySelector(".demande").textContent.toLowerCase().includes(filters.demande.value.toLowerCase()) &&
                    row.querySelector(".collab").textContent.toLowerCase().includes(filters.collab.value.toLowerCase()) &&
                    row.querySelector(".date_debut").textContent.toLowerCase().includes(filters.debut.value.toLowerCase()) &&
                    row.querySelector(".date_fin").textContent.toLowerCase().includes(filters.fin.value.toLowerCase()) &&
                    row.querySelector(".jours").textContent.toLowerCase().includes(filters.jours.value.toLowerCase())
                );

                row.style.display = match ? "table-row" : "none";
            });
        }

        document.querySelectorAll('.sortable').forEach(header => {
            let asc = true;
            header.addEventListener('click', () => {
                const table = header.closest('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr.card'));
                const columnIndex = parseInt(header.dataset.column);
                const type = header.dataset.type;
                const arrow = header.querySelector('.arrow');

                document.querySelectorAll('.sortable .arrow').forEach(el => el.textContent = '▲');

                rows.sort((a, b) => {
                    const aText = a.children[columnIndex].textContent.trim();
                    const bText = b.children[columnIndex].textContent.trim();

                    if (type === "number") {
                        return asc ? aText - bText : bText - aText;
                    } else if (type === "date") {
                        const parseDate = str => {
                            const parts = str.split(/[\/\s:h]/);
                            return new Date(`${parts[2]}-${parts[1]}-${parts[0]}T${parts[3] || "00"}:${parts[4] || "00"}`);
                        };
                        return asc ? parseDate(aText) - parseDate(bText) : parseDate(bText) - parseDate(aText);
                    } else {
                        return asc ? aText.localeCompare(bText) : bText.localeCompare(aText);
                    }
                });

                asc = !asc;
                arrow.textContent = asc ? '▲' : '▼';
                rows.forEach(row => tbody.appendChild(row));
                filterRows();
            });
        });
    </script>
</body>

</html>