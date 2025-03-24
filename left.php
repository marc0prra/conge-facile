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
      rel="stylesheet"
    />

    <link
      href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link rel="icon" type="image/x-icon" href="img/icone.ico" />

    <title>Accueil</title>
  </head>
</html>

<body>
  <div class="left">
    <a href="accueil.php">Accueil</a>
    <a href="nouvelle.php">Nouvelle demande</a>
    <a href="historique.php">Historique des demandes</a>
    <div class="rod"></div>
    <a href="infosC.php">Mes informations</a>
    <a href="preferences.php">Mes préférences</a>
    <a href="deconnexion.php">Déconnexion</a>
  </div>
  <button id="openMenu">☰ </button>
  <div id="menuOverlay"></div>
  <div class="leftMobile" id="menu">
   <span id="closeMenu">➜</span>
   <a href="accueil.php">Accueil</a>
    <a href="nouvelle.php">Nouvelle demande</a>
    <a href="historique.php">Historique des demandes</a>
    <div class="rod"></div>
    <a href="infosC.php">Mes informations</a>
    <a href="preferences.php">Mes préférences</a>
    <a href="deconnexion.php">Déconnexion</a>
  </div>

  <script src="script.js"></script>
</body>
