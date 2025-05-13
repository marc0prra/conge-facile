<?php
require 'config.php';  // Connexion à la base de données

// Vérifier si l'ID du poste est passé en GET (via l'URL)
if (isset($_GET['id'])) {
    $id_poste = $_GET['id'];

    // Récupérer les informations du poste à partir de la base de données
    $stmt = $pdo->prepare("SELECT * FROM position WHERE id = :id");
    $stmt->bindParam(':id', $id_poste, PDO::PARAM_INT);
    $stmt->execute();
    $posteSelectionne = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le poste n'existe pas
    if (!$posteSelectionne) {
        die("Le poste demandé n'existe pas.");
    }

    if (isset($_POST['modifier'])) {
        $nom = $_POST['nom'];
        $description = $_POST['description'];

        $sql = "UPDATE position SET name = :nom, nb_postes_dispo = :description WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id_poste, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: poste.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour.";
        }
    }

    if (isset($_POST['supprimer'])) {
        $sql = "DELETE FROM position WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id_poste, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: Poste.php");
            exit();
        } else {
            echo "Erreur lors de la suppression.";
        }
    }
} else {
    die("Aucun poste sélectionné.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Poste</title>
    <link rel="stylesheet" href="style.css?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
</head>
<!-- Modal de confirmation -->
<div id="confirmModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p>Êtes-vous sûr de vouloir supprimer ce poste ?</p>
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
                <h1 class="title_admin">Modifier le poste : <?= htmlspecialchars($posteSelectionne['name']) ?></h1>
                <form method="POST" class="form_admin">
                    <label for="nom" class="label_admin">Nom du poste</label>
                    <input type="text" id="nom" name="nom" class="input_admin"
                        value="<?= htmlspecialchars($posteSelectionne['name']) ?>" required>

                    <div class="button_container">
                        <button class="goBack"><a href="Poste.php">< Retour</a></button>
                        <button type="button" class="btn_red" onclick="openModal()">Supprimer</button>
                        <button type="submit" name="modifier" class="btn_blue">Mettre à jour</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
