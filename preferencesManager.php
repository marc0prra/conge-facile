<?php
require_once("include/config_bdd.php");
require_once("include/user.php");

$user_id = $_SESSION['user_id'];

// Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alerte = isset($_POST['alerte_conge']) ? 1 : 0;

    $sql = "UPDATE person SET alert_new_request = :alerte WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':alerte' => $alerte,
        ':user_id' => $user_id
    ]);
}

// Récupération de la valeur actuelle pour pré-remplir la case
$sql = "SELECT alert_new_request FROM person WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$userPref = $stmt->fetch(PDO::FETCH_ASSOC);
$isChecked = $userPref && $userPref['alert_new_request'] == 1 ? 'checked' : '';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes préférences</title>
    <link rel="stylesheet" href="style.css?v=2">
    <link rel="icon" href="img/MW_logo.png" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
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
                    <input type="checkbox" name="alerte_conge" class="switch" <?= $isChecked ?>>
                    <p>Être alerté par email lorsqu'une demande arrive</p>
                </div>
                <button type="submit" class="save">Enregistrer</button>
            </form>
        </div>
    </div>
</body>

</html>