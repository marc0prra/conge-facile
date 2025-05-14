<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

require_once 'config.php';

$erreur = "";
$success = "";

if (isset($_POST['ajouter'])) {
    $name = trim($_POST['titre']);

    if (empty($name)) {
        $erreur = "Veuillez entrer un nom de demande.";
    } else {
        $check = $pdo->prepare("SELECT COUNT(*) FROM request_type WHERE name = :name");
        $check->execute([':name' => $name]);
        $count = $check->fetchColumn();

        if ($count > 0) {
            $erreur = "Cette demande existe déjà.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO request_type (name) VALUES (:name)");
            $stmt->execute([':name' => $name]);
            $success = "Demande ajoutée avec succès.";
        }
    }
}

if (isset($_POST['annuler'])) {
    header("Location: requestList.php");
    exit;
}

// Récupère toutes les demandes existantes
$requete = $pdo->query("SELECT * FROM request_type ORDER BY name ASC");
$liste_demandes = $requete->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Ajouter une Demande</title>
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="ajout_demande_right">
        <div class="right">
            <h1>Ajouter une demande</h1>

            <?php if (!empty($erreur)) : ?>
                <div class="error"><?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)) : ?>
                <div class="success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="ajout_post">
                <form method="POST" class="form_admin">
                    <label for="titre" class="label_admin">Nom de la demande</label>
                    <input type="text" id="titre" name="titre" class="input_admin" >

                    <div class="button_container">
                        <button type="submit" name="annuler" class="btn_red">Annuler</button>
                        <button type="submit" name="ajouter" class="btn_blue">Ajouter</button>
                    </div>
                </form>
            </div>

            <h2>Demandes existantes</h2>
            <?php if (empty($liste_demandes)) : ?>
                <p>Aucune demande enregistrée.</p>
            <?php else : ?>
                <ul>
                    <?php foreach ($liste_demandes as $demande) : ?>
                        <li><?= htmlspecialchars($demande['name']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
