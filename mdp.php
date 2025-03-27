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

    <title>Mot de passe oublié</title>
  </head>
</html>
<body>
<?php include 'top.php'; ?>
  <div class="middle">
    <div class="left">
      <a href="connexion.php" class="active">Connexion</a>
    </div>
    <div class="right">
      <h1>Mot de passe oublié</h1>
      <div class="details">
        <p>Renseignez votre adresse email dans le champ ci-dessous.</p>
        <p>
          Vous receverez par la suite un email avec un lien vous permettant de
          réinitialiser votre mot de passe.
        </p>
      </div>
      <p>Adresse email</p>
      <input
        type="email"
        name="mail"
        placeholder=" ****@mentalworks.fr"
        required
        class="id" 
      />
      <button class="renit">
        Demander a réinitialiser votre mot de passe.
      </button>
      <p class="ps">
        <a href="connexion.php">Cliquez ici</a> pour retourner à la page de
        connexion.
      </p>
    </div>
  </div>
</body>
