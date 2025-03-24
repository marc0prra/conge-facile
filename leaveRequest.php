<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="Style.css" />

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

    <title> Demande de conge </title>
  </head>
  <div class="borderTop"></div>
  <div class="top">
    <img src="img/mentalworks.png" alt="Logo MentalWorks" />
  </div>
  <div class="middle">
    <div class="left">
      <a href="accueil.html">Accueil</a>
      <a href="nouvelle.html">Nouvelle demande</a>
      <a href="historique.html" class="active"> Historique des demandes</a>
      <div class="rod"></div>
      <a href="">Mes informations</a>
      <a href="">Mes préférences</a>
      <a href="">Déconnexion</a>
    </div>
    <div class="right">
        <h1> Ma demande de congé </h1>
        <p> Demande du </p>

        <div class="sectionRequestDetails">
            <p class="TypeRequest"> Type de congé : </p>
            <p class="TypeRequest"> Période : </p>
            <p class="TypeRequest"> Nombre de jours : </p>
            <p class="TypeRequest statuts"> Statut de la demande  : </p> <div class="statutRequest"></div>
            <div class="justificative RequestDetails">
              <p class="color">Commentaire du manager</p>
              <input class="mangerComment" type="text" name="comment" placeholder="">
            </div>
            <a href="#" class="moreDetails">Retourner à la liste de mes demandes</a>
        </div>