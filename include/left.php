<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php'; // $pdo est défini ici

$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['user_role'] ?? '1';
$user_prenom = 'Utilisateur';
$user_nom = '';

if ($user_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT person.last_name, person.first_name
            FROM user
            INNER JOIN person ON user.person_id = person.id
            WHERE user.id = ?
        ");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user_prenom = $user['first_name'];
            $user_nom = $user['last_name'];
        }
    } catch (PDOException $e) {
        $user_prenom = 'Erreur';
    }
}

// Comptage des demandes pour les managers
try {
    $queryCount = "SELECT COUNT(*) as total FROM request WHERE answer = 0";
    $stmtCount = $pdo->query($queryCount);
    $resultCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
    $nombreDemandes = $resultCount['total'];
} catch (PDOException $e) {
    $nombreDemandes = 'E';
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
  <link rel="icon" type="image/x-icon" href="img/icone.ico" />
  <title>Accueil</title>
</head>
<body>

<?php if ($user_role == '1'): ?>
  <!-- MENU COLLABORATEUR -->
  <div class="left">
    <a href="homePage.php">Accueil</a>
    <a href="nouvelle.php">Nouvelle demande</a>
    <a href="historique.php">Historique des demandes</a>
    <div class="rod"></div>
    <a href="infosC.php">Mes informations</a>
    <a href="preferences.php">Mes préférences</a>
    <a href="deconnexion.php">Déconnexion</a>
    <div class="skin">
      <div class="headSkin">
        <img src="img/téléchargement.png" alt="skin">
      </div>
      <div class="infoSkin">
        <strong class="nameSkin"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>
        <p class="typeSkin">Collaborateur</p>
      </div>
    </div>
  </div>

<?php elseif ($user_role == '2'): ?>
  <!-- MENU MANAGER -->
  <div class="left">
    <div class="collab"></div>
    <a href="homePage.php">Accueil</a>
    <a href="../manager/demandInExpectation.php">Demandes en attente <p class="numberRequest"><?= htmlspecialchars($nombreDemandes) ?> </p> </a>
    <a href="../manager/historyRequest.php">Historique des demandes</a>
    <a href="#">Mon équipe</a>
    <a href="../manager/statistique.php">Statistiques</a>
    <div class="rod"></div>
    <a href="../manager/infosManagers.php">Mes informations</a>
    <a href="manager/preferencesManager.php">Mes préférences</a>
    <div class="dropdown">
      <a href="#" class="dropbtn">Administration <i class="bx bx-chevron-down"></i></a>
      <div class="dropdown-content">
        <a href="../manager/demande.php">Types de demandes</a>
        <a href="../manager/direction.php">Directions/Services</a>
        <a href="../manager/Managers.php">Managers</a>
        <a href="../manager/Poste.php">Postes</a>
      </div>
    </div>
    <a href="deconnexion.php">Déconnexion</a>
    <div class="skin">
      <div class="headSkin">
        <img src="img/téléchargement (1).png" alt="skin">
      </div>
      <div class="infoSkin">
        <strong class="nameSkin"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>
        <p class="typeSkin">Manager</p>
      </div>
    </div>
  </div>
<?php endif; ?>

<script src="script.js"></script>
</body>
</html>
