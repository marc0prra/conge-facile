<?php
session_start();

include 'config.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = trim($_POST['mail']);
    $password = trim($_POST['mdp']);

    if (!empty($mail) && !empty($password)) {
        // Vérification de la connexion à la base de données
        if (!$conn) { 
            die("Erreur de connexion à la base de données.");
        }

        // Préparation de la requête SQL
        $sql = "SELECT id, password FROM user WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) { 
            $stmt->bind_param("s", $mail);
            $stmt->execute();
            $stmt->store_result();

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