<?php 

include 'user.php';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css?v=2" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="img/icone.ico" />
  <title>Accueil</title>
</head>

<body>

  <?php if ($user_role == '1'): ?>
    <!-- MENU COLLABORATEUR -->
    <div class="left">
      <a href="homePage.php">Accueil</a>
      <a href="newRequest.php">Nouvelle demande</a>
      <a href="employeeHistory.php">Historique des demandes</a>
      <div class="rod"></div>
      <a href="employeeInformation.php">Mes informations</a>
      <a href="userPreferences.php">Mes préférences</a>
      <a href="deconnexion.php">Déconnexion</a>
      <div class="skinCollab">
        <div class="headSkin">
          <img src="img/téléchargement.png" alt="skin">
        </div>
        <div class="infoSkin">
          <strong class="nameSkin"><?= $user_prenom . " " . $user_nom ?></strong>
          <p class="typeSkin">Collaborateur</p>
        </div>
      </div>
    </div>

  <?php elseif ($user_role == '2'): ?>
    <!-- MENU MANAGER -->
    <div class="left">
      <div class="collab"></div>
      <a href="homePage.php">Accueil</a>
      <a href="requestInExpectation.php">Demandes en attente <p class="numberRequest">
          <?= htmlspecialchars($nombreDemandes) ?>
        </p> </a>
      <a href="historyRequest.php">Historique des demandes</a>
      <a href="myTeam.php">Mon équipe</a>
      <a href="statistique.php">Statistiques</a>
      <div class="rod"></div>
      <a href="infosManagers.php">Mes informations</a>
      <a href="preferencesManager.php">Mes préférences</a>
      <div class="dropdown">
        <a href="#" class="dropbtn">Administration <i class="bx bx-chevron-down"></i></a>
        <div class="dropdown-content">
          <a href="requestList.php">Types de demandes</a>
          <a href="direction.php">Directions/Services</a>
          <a href="Managers.php">Managers</a>
          <a href="Poste.php">Postes</a>
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