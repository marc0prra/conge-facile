<?php
session_start();

include 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: connexion.php");
  exit();
}

// Message de succès
if (isset($_SESSION['success_message'])) {
    echo '<div class="containerNotif">
            <div class="notification notification--success">
                <div class="notification__body">
                    <img src="img/check.png" alt="Success" class="notification__icon">
                    ' . $_SESSION['success_message'] . '
                </div>
                <div class="notification__progress"></div>
            </div>
          </div>';
    unset($_SESSION['success_message']); 
}

// Données pour le graphique des types de demandes
$labels = [];
$values = [];

$typeQuery = "SELECT type, COUNT(*) as total FROM demande GROUP BY type";
$typeResult = mysqli_query($conn, $typeQuery);

if ($typeResult) {
    while ($row = mysqli_fetch_assoc($typeResult)) {
        $labels[] = $row['type'];
        $values[] = (int)$row['total'];
    }
}

// Données pour le graphique d'acceptation
$etatLabels = [];
$etatValues = [];

$etatQuery = "SELECT etat, COUNT(*) as total FROM demande GROUP BY etat";
$etatResult = mysqli_query($conn, $etatQuery);

if ($etatResult) {
    while ($row = mysqli_fetch_assoc($etatResult)) {
        $etatLabels[] = $row['etat'];
        $etatValues[] = (int)$row['total'];
    }
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Epilogue:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <title>Mentalworks</title>
    </head>
<body>
<?php
include 'include/top.php';
include 'include/left.php';
?>
<h2>Statistiques</h2>
<div style="max-width: 600px; margin-bottom: 2rem;">
    <h3>Types de demandes</h3>
    <canvas id="typeDemandesChart"></canvas>
</div>
<div style="max-width: 600px;">
    <h3>État des demandes</h3>
    <canvas id="acceptationChart"></canvas>
</div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const typeLabels = <?= json_encode($labels) ?>;
const typeValues = <?= json_encode($values) ?>;
const etatLabels = <?= json_encode($etatLabels) ?>;
const etatValues = <?= json_encode($etatValues) ?>;

const defaultColors = [
  'rgba(54, 162, 235, 0.8)',
  'rgba(255, 205, 86, 0.8)',
  'rgba(75, 192, 192, 0.8)',
  'rgba(255, 99, 132, 0.8)',
  'rgba(153, 102, 255, 0.8)',
  'rgba(255, 159, 64, 0.8)',
  'rgba(201, 203, 207, 0.8)'
];

// Graphique types de demandes
new Chart(document.getElementById('typeDemandesChart'), {
  type: 'doughnut',
  data: {
    labels: typeLabels,
    datasets: [{
      data: typeValues,
      backgroundColor: defaultColors.slice(0, typeLabels.length)
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});

// Graphique état des demandes
new Chart(document.getElementById('acceptationChart'), {
  type: 'pie',
  data: {
    labels: etatLabels,
    datasets: [{
      data: etatValues,
      backgroundColor: defaultColors.slice(0, etatLabels.length)
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>


    <script src="script.js"></script>
</body>
</html>