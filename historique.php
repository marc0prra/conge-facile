<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="style.css" />

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

    <title>Historique</title>
  </head>
</html>
<body>
<?php include 'top.php'; ?>
  <div class="middle">
        <?php include 'left.php'; ?>
    <div class="right">
        <h1>Historique de mes demandes</h1>
        <div class="sectionTab">
          <div class="topTab">
            <div class="categorie">
              <div class="title">
                Type de demande 
              </div>
              <div class="case">
                <input class="tab"></input>
              </div>
            </div>
            <div class="categorie">
              <div class="title">
                demandée le 
              </div>
              <div class="case">
                <input class="tab"></input>
              </div>
            </div>
            <div class="categorie">
              <div class="title">
                Date de début
              </div>
              <div class="case">
                <input class="tab"></input>
              </div>
            </div>
            <div class="categorie">
              <div class="title">
                Date de fin
              </div>
              <div class="case">
                <input class="tab"></input>
              </div>
            </div>
            <div class="categorie">
              <div class="title">
                Nb de jours
              </div>
              <div class="case">
                <input class="tab"></input>
              </div>
            </div>
            <div class="categorie">
              <div class="title">
              Statut
              </div>
              <div class="case">
                <input class="tab"></input>
              </div>
            </div>
          </div>
          <div class="bottomTab">
            <div class="requestTicket">
              <div class="request Type">
                Congé payer
              </div>
              <div class="request Date">
                12/12/2020
              </div>
              <div class="request Start">
                19/19/2020 19:00
              </div>
              <div class="request End">
                01/01/2020
              </div>
              <div class="request Days">
                3jours
              </div>
              <div class="request Statut">
                Refusé
              </div>
            </div>
            <div class="requestTicket">
              <div class="request Type">
                Congé payer
              </div>
              <div class="request Date">
                12/12/2020
              </div>
              <div class="request Start">
                19/19/2020 19:00
              </div>
              <div class="request End">
                01/01/2020
              </div>
              <div class="request Days">
                3jours
              </div>
              <div class="request Statut">
                Refusé
              </div>
            </div>
          </div>
        </div>
  </div>
    

