<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$success = false; // par défaut

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['mail'])) {
    $userEmail = $_POST['mail'];

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'resetmotdepasse00@gmail.com';
        $mail->Password = 'fonccymfcqtioqbv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('resetmotdepasse00@gmail.com', 'Support Mot de passe');
        $mail->addAddress($userEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation de votre mot de passe';
        $mail->Body = "
            <h1>Réinitialisation de mot de passe</h1>
            <p>Veuillez cliquer sur ce lien pour réinitialiser votre mot de passe.</p>
        ";

        $mail->send();
        $success = true;
    } catch (Exception $e) {
        echo "❌ Erreur lors de l'envoi : {$mail->ErrorInfo}";
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
    rel="stylesheet"
  />
  <link
    href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
    rel="stylesheet"
  />
  <link rel="icon" type="image/x-icon" href="img/icone.ico" />
  <title>Mot de passe oublié</title>

</head>

<body>
<?php include 'include/top.php'; ?>
<div class="middle">
  <div class="left">
    <a href="connexion.php" class="active">Connexion</a>
  </div>
  <div class="right">
    <h1>Mot de passe oublié</h1>
    <div class="details">
      <p>
        Renseignez votre adresse email dans le champ ci-dessous. Vous recevrez
        par la suite un email avec un lien vous permettant de réinitialiser
        votre mot de passe.
      </p>
    </div>

    <form action="mdp.php" method="POST">
      <p>Adresse email</p>
      <input type="email" name="mail" placeholder="****@mentalworks.fr" required class="id" />
      <button type="submit" class="renit">Demander à réinitialiser votre mot de passe</button>
    </form>

    <p class="ps">
      <a href="connexion.php">Cliquez ici</a> pour retourner à la page de connexion.
    </p>
  </div>
</div>

<?php if ($success): ?>
  <div class="overlay"></div>
  <div class="popup">
    <h2> Email envoyé !</h2>
    <p>Un lien de réinitialisation a été envoyé à votre adresse email.</p>
    <button onclick="closePopup()">Fermer</button>
  </div>

  <script>
    function closePopup() {
      document.querySelector('.popup').style.display = 'none';
      document.querySelector('.overlay').style.display = 'none';
    }
  </script>
<?php endif; ?>

</body>
</html>
