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

    <title>Nouvelle demande</title>
  </head>
</html>
<body>
  <div class="borderTop"></div>
  <div class="top">
    <img src="img/mentalworks.png" alt="" />
  </div>
  <div class="middle">
    <div class="left">
      <a href="accueil.html">Accueil</a>
      <a href="nouvelle.html" class="active">Nouvelle demande</a>
      <a href="historique.html">Historique des demandes</a>
      <div class="rod"></div>
      <a href="">Mes informations</a>
      <a href="">Mes préférences</a>
      <a href="">Déconnexion</a>
    </div>
    <div class="right">
      <h1 class="new">Effectuer une nouvelle demande</h1>
      <p class="color">Type de demande - champ obligatoire</p>
      <select name="Selectionner un type">
        <option value="">Sélectionner un type</option>
      </select>
      <div class="dates">
        <div class="begin">
          <p class="date" class="color">Date début - champ obligatoire</p>
          <input
            type="number"
            name="date"
            id=""
            placeholder=""
            style="
              background-image: url('img/calendar.png');
              background-size: 20px;
              background-position: 10px center;
              background-repeat: no-repeat;
            "
          />
        </div>
        <div class="end">
          <p class="date" class="color">Date de fin - champ obligatoire</p>
          <input
            type="number"
            name="date"
            style="
              background-image: url('img/calendar.png');
              background-size: 20px;
              background-position: 10px center;
              background-repeat: no-repeat;
            "
          />
        </div>
      </div>
      <div class="grey">
        <p class="greyP">Nombres de jours demandés</p>
        <input type="number" name="date" placeholder="0" />
      </div>
      <div class="files">
        <p class="color">Justificatif si applicable</p>
        <label for="justificative" class="file-label">Sélectionner un fichier</label>
        <input
          type="file"
          name="Justificative"
          id="justificative"
          class="file-input"
        />
    </div>
    <div class="justificative">
      <p class="color">Justificatif si applicable</p>
      <input
        type="text"
        name="text"
        id=""
        placeholder="Si congés exceptionnel ou sans solde, vous pouvez précisez votre demande."
      />
  </div>
  <div class="end">
    <button>Soumettre ma demande*</button>
    <p class="error">*En cas d'erreur de saisie ou de changements, vous pourrez modifier votre demande tant que celle-ci n'a pas été validée par le manager.</p>
  </div>
  </div>
</body>
