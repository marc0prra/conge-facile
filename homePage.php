<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: connexion.php");
  exit();
}


if (isset($_SESSION['success_message'])) {
    echo '<div class="containerNotif">
            <div class="notification notification--success">
                <div class="notification__body">
                    <img src="img/check.png" alt="Success" class="notification__icon">
                    ' . $_SESSION['success_message'] . '
                </div>
                <div class="notification__progress"></div>
            </div>
          </div>';
    unset($_SESSION['success_message']); 
}
?>


<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />


        <link rel="icon" href="img/MW_logo.png" type="image/png">


        <link rel="stylesheet" href="style.css?v=2" />

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
            rel="stylesheet" />

        <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />

        <title>Accueil</title>
    </head>

</html>



<body>
    <?php include 'include/top.php'; ?>
    <div class="middle">
        <?php include 'include/left.php'; ?>
        <div class="right">
            <h1>CongéFacile</h1>
            <div class="details">
                <p>
                    CongéFacile est votre nouvel outil dédié à la gestion des congés au
                    sein de l’entreprise.
                    Plus besoin d’échanges interminables ou de formulaires papier : en
                    quelques clics, vous pouvez gérer vos absences en toute transparence
                    et simplicité.
                </p>
            </div>
            <h2>Etapes</h2>
            <div class="steps-container">
                <div class="step current-step">
                    <div class="circle">1</div>
                    <p>Étape 1</p>
                </div>
                <div class="line"></div>
                <div class="step">
                    <div class="circle">2</div>
                    <p>Étape 2</p>
                </div>
                <div class="line"></div>
                <div class="step">
                    <div class="circle">3</div>
                    <p>Étape 3</p>
                </div>
            </div>
            <div class="blocks">
                <div class="block">
                    <p>J'effectue ma demande de congés</p>
                </div>
                <div class="block">
                    <p>Mon manager valide ou refuse la demande</p>
                </div>
                <div class="block">
                    <p>Je consulte l'historique de mes demandes</p>
                </div>
            </div>
            <p class="diff">
                En cas de difficulté avec l'application, veuillez envoyer un email à
                <a href="#.php" class="blue">contact@mentalworks.fr</a>.
            </p>
        </div>
    </div>

</body>