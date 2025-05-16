<?php
session_start();

include 'include/tabDonne.php';

include 'config.php';

$demandes = [];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=congefacile', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT 
                request.id,
                request.request_type_id,
                request_type.name AS type_demande, 
                request.created_at AS date_demande, 
                request.start_at AS date_debut, 
                request.end_at AS date_fin,
                request.answer AS etat_demande,
                person.first_name AS prenom,
                person.last_name AS nom
              FROM request
              JOIN request_type ON request.request_type_id = request_type.id
              JOIN person ON request.collaborator_id = person.id  -- Joindre la table person pour récupérer le nom et prénom
              WHERE request.answer =0 ";  // Filtrer pour les demandes en cours

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
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
    
    <title>Historique</title>
    <style>
        .searchListe { width: 100%; margin-top: 5px; }
        .sortable { cursor: pointer; }
        .arrow { margin-left: 5px; font-size: 0.8em; }
        </style>
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
                            <td class = "type_demande" ><?= htmlspecialchars($demande['type_demande']) ?></td>
                            <td class = "demande" ><?= htmlspecialchars((new DateTime($demande['date_demande']))->format('d/m/Y H\hi')) ?></td>
                            <td class = "collab" ><?= htmlspecialchars($demande['prenom']) ?> <?= htmlspecialchars($demande['nom']) ?></td>
                            <td class = "date_debut" ><?= htmlspecialchars((new DateTime($demande['date_debut']))->format('d/m/Y H\hi')) ?></td>
                            <td class = "date_fin" ><?= htmlspecialchars((new DateTime($demande['date_fin']))->format('d/m/Y H\hi')) ?></td>
                            <td class = "jours" ><?= getWorkingDays($demande['date_debut'], $demande['date_fin'], $holidays); ?></td>
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