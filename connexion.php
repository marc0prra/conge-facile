<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL);
    $password = $_POST['mdp'];
    
    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id, mot_de_passe FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: mdp.html");
            exit();
        } else {
            echo "<p style='color:red;'>Identifiants incorrects.</p>";
        }
    } else {
        echo "<p style='color:red;'>Veuillez remplir tous les champs.</p>";
    }
}
?>
