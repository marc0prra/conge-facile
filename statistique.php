<?php  
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

require_once 'config.php'; // Connexion DB

// Message de succès
if (isset($_SESSION['success_message'])) {
    echo '<div class="containerNotif">
            <div class="notification notification--success">
                <div class="notification__body">
                    <img src="img/check.png" alt="Success" class="notification__icon">
                    ' . $_SESSION['success_message'] . '
                </div>
                <div class="notification__progress"></div>
          </div></div>';
    unset($_SESSION['success_message']); 
}

// Récupération des données pour les types de demandes (nombre et pourcentage)
$labels = [];
$values = [];
$pct = [];

$sql = "
    SELECT rt.name AS type, COUNT(r.id) AS total,
           ROUND(100.0 * COUNT(r.id) / t.total, 2) AS pourcentage
    FROM request_type rt
    LEFT JOIN request r ON r.request_type_id = rt.id
    CROSS JOIN (SELECT COUNT(*) AS total FROM request) t
    GROUP BY rt.name, t.total
";

$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $labels[] = $row['type'];
        $values[] = (int)$row['total'];
        $pct[] = (float)$row['pourcentage'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques des demandes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=2">
    <link rel="icon" href="img/MW_logo.png" type="image/png"> 

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;900&family=Inter:wght@100;900&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <div class="container_admin">
            <h2>Statistiques </h2>
            <div style="max-width: 600px; margin-bottom: 2rem;">
                <h3>Types de demandes sur l'année</h3>
                <canvas id="percentageChart"></csanvas>
              </div>
              <div style="max-width: 600px;">
                <h3>Pourcentage d'acceptation des demandes sur l'année</h3>
                <canvas id="typeChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
const labels = <?= json_encode($labels) ?>;
const values = <?= json_encode($values) ?>;
const percentages = <?= json_encode($pct) ?>;

const colors = [
  'rgba(255, 99, 132, 0.8)',
  'rgba(54, 162, 235, 0.8)',
  'rgba(255, 206, 86, 0.8)',
  'rgba(75, 192, 192, 0.8)',
  'rgba(153, 102, 255, 0.8)',
  'rgba(255, 159, 64, 0.8)',
  'rgba(199, 199, 199, 0.8)'
];

// Bar Chart - Nombre de demandes
new Chart(document.getElementById('typeChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Nombre de demandes',
            data: values,
            backgroundColor: colors.slice(0, labels.length)
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: true, text: 'Nombre de demandes par type' }
        }
    }
});

// Pie Chart - Pourcentage
new Chart(document.getElementById('percentageChart'), {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
            label: '% des demandes',
            data: percentages,
            backgroundColor: colors.slice(0, labels.length)
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.label}: ${context.parsed}%`;
                    }
                }
            }
        }
    }
});
</script>
</body>
</html>