<?php 
session_start();

if (!isset($_SESSION['managers'])) {
    $_SESSION['managers'] = [];
}

$managers = &$_SESSION['managers'];

if (isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $service = trim($_POST['service']);

    if (!empty($nom) && !empty($prenom) && !empty($service)) {
        $nextId = !empty($managers) ? max(array_column($managers, 'id')) + 1 : 1;

        $managers[] = [
            'id' => $nextId,
            'nom' => $nom,
            'prenom' => $prenom,
            'service' => $service
        ];

        header("Location: Managers.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css?v=2" />
    <title>Ajouter un Manager</title>
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <h1>Ajouter un manager</h1>
        <div class="ajout_post">
            <form method="POST" class="form_admin">
                <label for="nom" class="label_admin">Nom de famille</label>
                <input type="text" id="nom" name="nom" class="input_admin" >

                <label for="prenom" class="label_admin">Prénom</label>
                <input type="text" id="prenom" name="prenom" class="input_admin" >

                <label for="service" class="label_admin">Service</label>
                <input type="text" id="service" name="service" class="input_admin" >

                <div class="button_container">
                    <a href="Managers.php" class="btn_red">Annuler</a>
                    <button type="submit" name="ajouter" class="btn_blue">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
