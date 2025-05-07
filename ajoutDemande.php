<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

require_once 'config.php';

if (isset($_POST['ajouter'])) {
    $name = trim($_POST['titre']);

    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO request_type (name) VALUES (:name)");
        $stmt->execute([
            ':name' => $name
        ]);

        header("Location: demande.php");
        exit;
    }
}

if (isset($_POST['annuler'])) {
    header("Location: demande.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout de Demande</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="ajout_demande_right">
        <div class="right">
            <h1>Ajoutez une demande</h1>
            <div class="ajout_post">
                <form method="POST" class="form_admin">
                    <label for="titre" class="label_admin">Nom de la demande</label>
                    <input type="text" id="titre" name="titre" class="input_admin" required>

                    <div class="button_container">
                        <button type="submit" name="annuler" class="btn_red">Annuler</button>
                        <button type="submit" name="ajouter" class="btn_blue">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
