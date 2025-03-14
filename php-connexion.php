<?php
session_start();

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config.php';

$error_message = "";  // Initialisation de la variable d'erreur

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = trim($_POST['mail']);
    $password = trim($_POST['mdp']);

    if (!empty($mail) && !empty($password)) {
        if (!$conn) { 
            die("Erreur de connexion à la base de données.");
        }

        // Préparer la requête SQL
        $sql = "SELECT id, password FROM user WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) { 
            $stmt->bind_param("s", $mail);
            $stmt->execute();
            $stmt->store_result();

            // Si l'utilisateur existe dans la base
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $hashed_password);
                $stmt->fetch();

                // Vérification du mot de passe
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $id;
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error_message = "Adresse email ou mot de passe incorrect.";
                }
            } else {
                $error_message = "Adresse email ou mot de passe incorrect.";
            }
            $stmt->close();
        } else {
            $error_message = "Erreur lors de la préparation de la requête.";
        }
    } else {
        $error_message = "Veuillez remplir tous les champs.";
    }
}
?>