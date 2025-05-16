<?php
require 'config.php'; 

$erreur = "";
$success = "";
$name = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['titre']);

    if (empty($name)) {
        $erreur = "Veuillez entrer un nom de poste.";
    } else {
        $check = $pdo->prepare("SELECT COUNT(*) FROM position WHERE name = :name");
        $check->execute([':name' => $name]);
        $count = $check->fetchColumn();

        if ($count > 0) {
            $erreur = "Ce poste existe déjà.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO position (name) VALUES (:name)");
                $stmt->bindParam(':name', $name);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $success = "Poste ajouté avec succès.";
                    $name = "";
                } else {
                    $erreur = "Aucune donnée n'a été insérée.";
                }
            } catch (PDOException $e) {
                $erreur = "Erreur lors de l'ajout du poste : " . $e->getMessage();
            }
        }
    }
}

// Récupération des postes existants
$liste_postes = $pdo->query("SELECT * FROM position ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un poste</title>
    <link rel="stylesheet" href="style.css?v=2">
    <link rel="icon" href="img/MW_logo.png" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Epilogue&family=Inter&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <style>
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <div class="container_admin">
            <h1 class="title_admin">Ajouter un poste</h1>

            <?php if (!empty($success)) : ?>
                <div class="success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" class="form_admin">
                <label for="titre" class="label_admin">Nom du poste</label>
                <input type="text" id="titre" name="titre" class="input_admin" value="<?= htmlspecialchars($name) ?>">
                <?php if (!empty($erreur)) : ?>
                    <div class="error"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <div class="button_container">
                    <button type="button" class="btn_red"><a href="poste.php">Annuler</a></button>
                    <button type="submit" class="btn_blue">Ajouter</button>
                </div>
            </form>

            <h2>Postes existants</h2>
            <?php if (empty($liste_postes)) : ?>
                <p>Aucun poste enregistré.</p>
            <?php else : ?>
                <ul>
                    <?php foreach ($liste_postes as $poste) : ?>
                        <li><?= htmlspecialchars($poste['name']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
