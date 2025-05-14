<?php 
require 'config.php'; // Connexion BDD

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouter'])) {
        $titre = trim($_POST['titre'] ?? '');

        if (!empty($titre)) {
            $stmt = $pdo->prepare("INSERT INTO department (name) VALUES (:name)");
            $stmt->bindParam(':name', $titre, PDO::PARAM_STR);
            $stmt->execute();

            header("Location: direction.php");
            exit;
        }
    }

    if (isset($_POST['annuler'])) {
        header("Location: direction.php");
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
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <title>Ajouter une direction</title>
</head>

<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <h1>Ajouter une direction</h1>
        <div class="ajout_post">
            <form method="POST" class="form_admin">
                <label for="titre" class="label_admin">Nom de la direction</label>
                <input type="text" id="titre" name="titre" class="input_admin" >

                <div class="button_container">
                    <button type="submit" name="annuler" class="btn_red">Annuler</button>
                    <button type="submit" name="ajouter" class="btn_blue">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
