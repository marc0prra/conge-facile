<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $alerte_conge = isset($_POST["alerte_conge"]) ? 1 : 0;
    $rappel_conge = isset($_POST["rappel_conge"]) ? 1 : 0;

    // Création des cookies valables 30 jours
    setcookie("alerte_conge", $alerte_conge, time() + 30 * 24 * 60 * 60, "/");
    setcookie("rappel_conge", $rappel_conge, time() + 30 * 24 * 60 * 60, "/");

    // Recharge la page pour appliquer les changements
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préférences de congé</title>
</head>
<body>
<?php include 'top.php'; ?>
  <div class="middle">
        <?php include 'left.php'; ?>
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
