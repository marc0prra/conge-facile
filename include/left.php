<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupération du rôle et du prénom de l'utilisateur depuis la session
$user_role = $_SESSION['user_role'] ?? '1';
$user_prenom = $_SESSION['user_prenom'] ?? 'Utilisateur';

// Vérification de l'existence de la connexion PDO
if (!isset($pdo)) {
    require_once 'config.php'; // Assure-toi que ce fichier initialise correctement $pdo
}

try {
    // Requête pour compter les demandes non traitées (answer = 0)
    $queryCount = "SELECT COUNT(*) as total FROM request WHERE answer = 0";
    $stmtCount = $pdo->query($queryCount);
    $resultCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
    $nombreDemandes = $resultCount['total'];
} catch (PDOException $e) {
    // En cas d'erreur, on affiche 'E' pour éviter une interruption du site
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
    <a href="accueil.php">Accueil</a>
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
        <strong class="nameSkin"><?= htmlspecialchars($user_prenom) ?></strong>
        <p class="typeSkin">Collaborateur</p>
      </div>
    </div>
  </div>

<?php elseif ($user_role == '2'): ?>
  <!-- MENU MANAGER -->
  <div class="left">
    <div class="collab"></div>
    <a href="accueil.php">Accueil</a>
    <a href="demandInExpectation.php">Demandes en attente <?= htmlspecialchars($nombreDemandes) ?> </a>
    <a href="historique.php">Historique des demandes</a>
    <a href="#">Mon équipe</a>
    <a href="#">Statistiques</a>
    <div class="rod"></div>
    <a href="infosM.php">Mes informations</a>
    <a href="preferencesManager.php">Mes préférences</a>
    <div class="dropdown">
      <a href="#" class="dropbtn">Administration <i class="bx bx-chevron-down"></i></a>
      <div class="dropdown-content">
        <a href="demande.php">Types de demandes</a>
        <a href="direction.php">Directions/Services</a>
        <a href="Managers.php">Managers</a>
        <a href="poste.php">Postes</a>
      </div>
    </div>
    <a href="deconnexion.php">Déconnexion</a>
    <div class="skin">
      <div class="headSkin">
        <img src="img/téléchargement (1).png" alt="skin">
      </div>
      <div class="infoSkin">
        <strong class="nameSkin"><?= htmlspecialchars($user_prenom) ?></strong>
        <p class="typeSkin">Manager</p>
      </div>
    </div>
  </div>
<?php endif; ?>

<script src="script.js"></script>
</body>
</html>
