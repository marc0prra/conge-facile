<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $alerte_conge = isset($_POST["alerte_conge"]) ? 1 : 0;
    $rappel_conge = isset($_POST["rappel_conge"]) ? 1 : 0;

    // Durée de vie des cookies : 30 jours
    $expiry = time() + (30 * 24 * 60 * 60);

    setcookie("alerte_conge", $alerte_conge, $expiry, "/");
    setcookie("rappel_conge", $rappel_conge, $expiry, "/");

    // Rediriger pour éviter le renvoi du formulaire si on rafraîchit la page
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services</title>
    <link rel="stylesheet" href="style.css?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
<?php include 'include/top.php'; ?>
  <div class="middle">
        <?php include 'include/left.php'; ?>
            <div class="right">
                <h1>Mes préférences</h1>
            <form method="post">
                <div class="button">
                    <input type="checkbox" name="alerte_conge" class="switch" 
                     <?php if(isset($_COOKIE["alerte_conge"]) && $_COOKIE["alerte_conge"] == 1) echo "checked"; ?>>
                    <p>Être alerté par email lorsqu'une demande arrive</p>
                </div>
                <button type="submit" class="save">Enregistrer</button>
            </form>
        </div>
</body>
</html>
