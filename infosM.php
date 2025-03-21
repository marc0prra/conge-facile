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

    <title>Mes informations</title>
  </head>
</html>
<body>
  <div class="borderTop"></div>
  <div class="top">
    <img src="img/mentalworks.png" alt="" />
  </div>
  <div class="middle">
    <div class="left">
      <a href="accueil.php">Accueil</a>
      <a href="nouvelle.php">Nouvelle demande</a>
      <a href="">Historique des demandes</a>
      <div class="rod"></div>
      <a href="infosC.php" class="active">Mes informations</a>
      <a href="">Mes préférences</a>
      <a href="deconnexion.php">Déconnexion</a>
    </div>
    <div class="right">
      <h1>Mes informations</h1>
      <form action="infosC.php"  method="POST">
        <div class="infos">
          <div class="name">
            <p>Nom de famille</p>
            <input type="text" placeholder="" required/>
          </div>
          <div class="nameF">
            <p>Prénom</p>
            <input type="text" placeholder="" required/>
          </div>
        </div>
        <div class="email">
          <p>Adresse email</p>
          <input
            type="text"
            placeholder=""
            style="
              background-image: url('img/email.png');
              background-size: 20px;
              background-position: 10px center;
              background-repeat: no-repeat;
            "
            required
          />
        </div>
        <div class="service">
          <div class="services">
            <div class="direction">
              <p>Direction/Service</p>
              <select name="direction" required>
                <option value="1">BU Symfony</option>
                <option value="2">Mentalworks</option>
              </select>
            </div>
            <div class="poste">
              <p>Poste</p>
              <select name="poste" required>
                <option value="1">Directeur technique</option>
                <option value="2">Lead developper</option>
              </select>
            </div>
          </div>
          <div class="manager">
            <p>Manager</p>
            <select name="manager" required>
              <option value="1">Frédéric Salesses</option>
              <option value="2">test test</option>
            </select>
          </div>
        </div>
        <div class="">
          <h2>Réinitialiser son mot de passe</h2>
          <div class="forgot">
                    <p class="color">Mot de passe actuel</p>
                    <input type="password" id="password">
                    <img
                        src="img/open-eye.png"
                        alt="Afficher"
                        class="toggle-password"
                        onclick="togglePassword('password', this)"
                    >
                </div>
        <div class="mdp">
                    <div class="forgotN">
                        <p class="color">Nouveau mot de passe</p>
                        <input type="password" id="newPassword">
                        <img
                            src="img/open-eye.png"
                            alt="Afficher"
                            class="toggle-password"
                            onclick="togglePassword('newPassword', this)"
                        >
                    </div>

            <div class="forgotF">
                        <p class="color">Confirmation du mot de passe</p>
                        <input type="password" id="confirmPassword">
                        <img
                            src="img/open-eye.png"
                            alt="Afficher"
                            class="toggle-password"
                            onclick="togglePassword('confirmPassword', this)"
                        >
                </div>
          </div>
        </div>
      </form>
      <button class="initial"><a href="mdp.php" class="white">Réinitialiser le mot de passe</a></button>
    </div>
  </div>
  <script src="script.js"></script>
</body>
