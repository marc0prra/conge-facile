<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config.php';
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = trim($_POST['mail']);
    $password = trim($_POST['mdp']);

    if (!empty($mail) && !empty($password)) {
        if (!$conn) {
            die("Erreur de connexion à la base de données.");
        }

        // Requête pour récupérer les informations utilisateur + department_id
        $sql = "SELECT u.id, u.password, p.department_id, position_id 
                FROM user u 
                JOIN person p ON u.id = p.id 
                WHERE u.email = ?";
        
        if ($stmt = $conn->prepare($sql)) { 
            $stmt->bind_param("s", $mail);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $hashed_password, $department_id, $user_role);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['department_id'] = $department_id;
                    $_SESSION['user_role'] = $user_role;
                    if ($user_role == 1) {
                        $_SESSION['success_message'] = "Connecté en tant que Collaborateur !";
                    } elseif ($user_role == 2) {
                        $_SESSION['success_message'] = "Connecté en tant que Manageur !";
                    }
                    header("Location: homePage.php");
                    exit();
                } else {
                    $error_message = "L'adresse email ou le mot de passe est incorrect.";
                }
            } else {
                $error_message = "L'adresse email ou le mot de passe est incorrect.";
            }
            $stmt->close();
        } else {
            $error_message = "Erreur lors de la préparation de la requête.";
        }
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
    <link
        href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />

    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="img/icone.ico" />

    <title>Accueil</title>
</head>

<body>
    <?php include 'include/top.php'; ?>
    <div class="middle">
        <div class="left">
            <a href="connexion.php" class="active">Connexion</a>
        </div>
        <div class="right">
            <h1>CongéFacile</h1>
            <div class="details">
                <p>
                    CongéFacile est votre nouvel outil dédié à la gestion des congés au
                    sein de l’entreprise.
                    Plus besoin d’échanges interminables ou de formulaires papier : en
                    quelques clics, vous pouvez gérer vos absences en toute transparence
                    et simplicité. Connectez-vous ci-dessous pour accéder à votre espace.
                </p>
            </div>

            <h2 class="connexion">Connectez-vous</h2>
            <form action="connexion.php" method="POST">
                <p>Adresse email</p>
                <input type="email" name="mail" placeholder="****@mentalworks.fr" class="mailInput" required class="id" style="
                        background-image: url('img/email.png');
                        background-size: 20px;
                        background-position: 10px center;
                        background-repeat: no-repeat;
                    "/>
                <p>Mot de passe</p>
                <input type="password" name="mdp" required  id="password" />
                <img src="img/open-eye.png" alt="Afficher" class="toggle" onclick="togglePassword('password', this)" />
                <div style="color:red; margin-top: 10px;">
                    <?php if (!empty($error_message)) { echo $error_message; } ?>
                </div>
                <button type="submit" class="portal">Connexion au portail</button>
            </form>

            <p class="ps">
                Vous avez oublié votre mot de passe ?
                <a href="mdp.php">Cliquez ici</a> pour le réinitialiser.
            </p>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>