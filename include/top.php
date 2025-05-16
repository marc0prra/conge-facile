<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php'; 

$user_role = $_SESSION['user_role'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

$user_prenom = 'Utilisateur';
$user_nom = '';
$nombreDemandes = 0;

if ($user_id) {
    try {
        $stmt = $pdo->prepare("SELECT prenom, nom FROM utilisateurs WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $user_prenom = $user['prenom'];
            $user_nom = $user['nom'];
        }
    } catch (PDOException $e) {
        // En cas d’erreur
    }
}

if ($user_role === '2') {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM request WHERE answer = 0");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombreDemandes = $result['total'] ?? 0;
    } catch (PDOException $e) {
        $nombreDemandes = '!';
    }
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

    <link rel="icon" href="img/MW_logo.png" type="image/png">
    
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"/>

    <title>Accueil</title>
  </head>
</html>

<body>
  <div class="allTop">
  <div class="borderTop"></div>
  <div class="top">
    <a href="homePage.php">
      <img src="img/mentalworks.png" alt="Logo MentalWoks" />
    </a>
      

<!-- Bouton burger -->
  <div id="openMenu" class="burger">
    <span></span>
    <span></span>
    <span></span>
  </div>
  </div>

<!-- Overlay -->
<div id="menuOverlay" class="menu-overlay"></div>

<!-- Menu mobile -->
<div id="menu" class="mobile-menu">
  <div id="closeMenu" class="close-btn">×</div>

  <?php if ($user_role == '1'): ?>
    <a href="homePage.php">Accueil</a>
    <a href="nouvelle.php">Nouvelle demande</a>
    <a href="historique.php">Historique</a>
    <div class="rod"></div>
    <a href="infosC.php">Mes infos</a>
    <a href="preferences.php">Préférences</a>
    <a href="deconnexion.php">Déconnexion</a>
  <?php elseif ($user_role == '2'): ?>
    <a href="homePage.php">Accueil</a>
    <a href="requestInExpectation.php">Demandes (<?= htmlspecialchars($nombreDemandes) ?>)</a>
    <a href="historyRequest.php">Historique</a>
    <a href="#">Mon équipe</a>
    <a href="statistique.php">Statistiques</a>
    <div class="rodMenu"></div>
    <a href="infosManagers.php">Mes infos</a>
    <a href="preferencesManager.php">Préférences</a>
    <div class="dropdownBurger">
      <a href="#" class="dropbtnBurger">Administration <i class="bx bx-chevron-down"></i></a>
      <div class="dropdown-contentBurger">
        <a href="demande.php">Types de demandes</a>
        <a href="direction.php">Directions/Services</a>
        <a href="Managers.php">Managers</a>
        <a href="Poste.php">Postes</a>
      </div>
    </div>
    <a href="deconnexion.php">Déconnexion</a>
  <?php endif; ?>
</div>


    
  </div>
</body>
