<?php
require 'config.php'; // Connexion à la base

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM department WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$direction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$direction) {
    die("Erreur : Direction introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['supprimer'])) {
        $stmt = $pdo->prepare("DELETE FROM department WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: direction.php");
        exit;
    }

    if (isset($_POST['modifier'])) {
        $titre = trim($_POST['titre']);
        if (!empty($titre)) {
            $stmt = $pdo->prepare("UPDATE department SET name = :name WHERE id = :id");
            $stmt->bindParam(':name', $titre, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: direction.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails des Directions</title>
    <link rel="stylesheet" href="style.css?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
</head>
<!-- Modal de confirmation -->
<div id="confirmModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p>Êtes-vous sûr de vouloir supprimer cette direction/service ?</p>
        <div class="modal-buttons">
            <form method="POST">
                <input type="hidden" name="supprimer" value="1">
                <button type="submit" class="btn_red">Oui, supprimer</button>
            </form>
            <button onclick="closeModal()" class="btn_blue">Annuler</button>
        </div>
    </div>
</div>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <div class="container_admin">
            <h1 class="title_admin">Direction / Service</h1>
            <form method="POST" class="form_admin">
                <label for="titre" class="label_admin">Nom de la direction</label>
                <input type="text" id="titre" name="titre" class="input_admin"
                       value="<?= htmlspecialchars($direction['name']) ?>" required>

                <div class="button_container">
                    <button class="goBack"><a href="direction.php">< Retour</a></button>
                    <button type="button" class="btn_red" onclick="openModal()">Supprimer</button>
                    <button type="submit" name="modifier" class="btn_blue">Mettre à jour</button>

                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
