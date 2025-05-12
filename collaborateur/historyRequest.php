<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

include 'config.php';

$demandes = [];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=congefacile', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL modifiée pour récupérer également le nom et prénom
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
              WHERE request.answer =2 OR request.answer =1 ";  // Filtrer pour les demandes en cours

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

?>
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
    <title>Historique</title>
</head>
<body>
<?php include 'include/top.php'; ?>

<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right historique">
        <h1> Historique des demandes </h1>
        <div class="container">    
            <table class="table1">
            <thead>
                <tr class='grey_Poste'>
                    <th class='searchHistorique sortable' data-column="0" data-type="text">
                        Type de demande <span class="arrow">▲</span><br>
                        <input id="search-type" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="1" data-type="text">
                        Collaborateur <span class="arrow">▲</span><br>
                        <input id="search-collab" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="2" data-type="date">
                        Date de début <span class="arrow">▲</span><br>
                        <input id="search-debut" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="3" data-type="date">
                        Date de fin <span class="arrow">▲</span><br>
                        <input id="search-fin" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="4" data-type="number">
                        Nb de jours <span class="arrow">▲</span><br>
                        <input id="search-jours" class='searchListe' type="text" />
                    </th>
                    <th class='searchHistorique sortable' data-column="5" data-type="text">
                        Statut <span class="arrow">▲</span><br>
                        <input id="search-statut" class='searchListe' type="text" />
                    </th>
                    <th></th>
                </tr>
            </thead>
            <?php 
            $holidays = [
                "2025-01-01",
                "2025-04-21",
                "2025-05-01",
                "2025-05-08",
                "2025-05-29",
                "2025-06-09",
                "2025-07-14",
                "2025-08-15",
                "2025-11-01",
                "2025-11-11",
                "2025-12-25"
            ];

            function getWorkingDays($start, $end, $holidays = []) {
                $begin = new DateTime($start);
                $end = new DateTime($end);
                $end->modify('+1 day');

                $interval = new DateInterval('P1D');
                $dateRange = new DatePeriod($begin, $interval, $end);

                $workingDays = 0;
                foreach ($dateRange as $date) {
                    $day = $date->format('N'); // 6 = samedi, 7 = dimanche
                    $formatted = $date->format('Y-m-d');
                        if ($day < 6 && !in_array($formatted, $holidays)) {
                            $workingDays++;
                        }
                }
                return $workingDays;
            }

            function getStatus($codes) {
                // Convertir la chaîne en tableau
                $numbers = explode(',', $codes);
            
                // Vérifier les numéros dans l'ordre de priorité
                if (in_array('0', $numbers)) {
                    return 'En cours';
                } elseif (in_array('1', $numbers)) {
                    return 'Acceptée';
                } elseif (in_array('2', $numbers)) {
                    return 'Refusée';
                } else {
                    return 'Statut inconnu';
                }
            }
            
            ?>
                <tbody>
                    <?php if (count($demandes) > 0) : ?>
                        <?php foreach ($demandes as $demande) : ?>
                            <tr class="card">
                            <td class = "type_demande" ><?= htmlspecialchars($demande['type_demande']) ?></td>
                            <td class = "collab" ><?= htmlspecialchars($demande['prenom']) ?> <?= htmlspecialchars($demande['nom']) ?></td>
                            <td class = "date_debut" ><?= htmlspecialchars((new DateTime($demande['date_debut']))->format('d/m/Y H\hi')) ?></td>
                            <td class = "date_fin" ><?= htmlspecialchars((new DateTime($demande['date_fin']))->format('d/m/Y H\hi')) ?></td>
                            <td class = "jours" ><?= getWorkingDays($demande['date_debut'], $demande['date_fin'], $holidays); ?></td>
                            <td class= "Statut" ><?= getStatus($demande['etat_demande']) ?></td>
                            <td>
                                <a class="det-button" href="viewARequest.php?id=<?= $demande['id']; ?>">
                                    <button class="det-button">Détails</button>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="empty-row">Aucune demande</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    const filters = {
        type: document.querySelector("#search-type"),
        collab: document.querySelector("#search-collab"),
        debut: document.querySelector("#search-debut"),
        fin: document.querySelector("#search-fin"),
        jours: document.querySelector("#search-jours"),
        statut: document.querySelector("#search-statut")
    };

    Object.values(filters).forEach(input => {
        input.addEventListener("keyup", filterRows);
    });

    function filterRows() {
        const rows = document.querySelectorAll(".card");

        rows.forEach(row => {
            const match = (
                row.querySelector(".type_demande").textContent.toLowerCase().includes(filters.type.value.toLowerCase()) &&
                row.querySelector(".collab").textContent.toLowerCase().includes(filters.collab.value.toLowerCase()) &&
                row.querySelector(".date_debut").textContent.toLowerCase().includes(filters.debut.value.toLowerCase()) &&
                row.querySelector(".date_fin").textContent.toLowerCase().includes(filters.fin.value.toLowerCase()) &&
                row.querySelector(".jours").textContent.toLowerCase().includes(filters.jours.value.toLowerCase()) &&
                row.querySelector(".Statut").textContent.toLowerCase().includes(filters.jours.value.toLowerCase())
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