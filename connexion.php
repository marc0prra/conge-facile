




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

</html>

<body>
    <div class="borderTop"></div>
    <div class="top">
        <img src="img/mentalworks.png" alt="" />
    </div>
    <div class="middle">
        <div class="left">
            <a href="connexion.html" class="active">Connexion</a>
        </div>
        <div class="right">
            <h1>CongéFacile</h1>
            <div class="details">
                <p>
                    CongéFacile est votre nouvel outil dédié à la gestion des congés au
                    sein de l’entreprise.
                </p>
                <p>
                    Plus besoin d’échanges interminables ou de formulaires papier : en
                    quelques clics, vous pouvez gérer vos absences en toute transparence
                    et simplicité. Connectez-vous ci-dessous pour accéder à votre espace.
                </p>
            </div>

            <h2>Connectez-vous</h2>
            <form action="php-connexion.php" method="POST">
                <p>Adresse email</p>
                <input type="email" name="mail" placeholder="****@mentalworks.fr" required class="id" />
                <p>Mot de passe</p>
                <input type="password" name="mdp" required class="id" />
                <?php if (!empty($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>
                <button type="submit" class="portal">Connexion au portail</button>
            </form>


            <p class="ps">
                Vous avez oublié votre mot de passe ?
                <a href="mdp.php">Cliquez ici</a> pour le réinitialiser.
            </p>
        </div>
    </div>
</body>
