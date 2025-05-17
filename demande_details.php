<?php
require_once("include/config_bdd.php");
require_once("include/user.php");

if (!isset($_GET['id'])) {
    die("Aucune demande sélectionnée.");
}

$id = (int) $_GET['id'];

// Récupérer les informations de la demande depuis la BDD
$stmt = $pdo->prepare("SELECT * FROM request_type WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$demande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$demande) {
    die("Erreur : Type de demande introuvable.");
}

// Suppression
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["supprimer"])) {
        $stmt = $pdo->prepare("DELETE FROM request_type WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            header("Location: requestList.php");
            exit();
        } else {
            echo "Erreur lors de la suppression.";
        }
    }

    // Modification
    if (isset($_POST["modifier"])) {
        $titre = $_POST['nom'];

        $stmt = $pdo->prepare("UPDATE request_type SET name = :titre WHERE id = :id");
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            header("Location: requestList.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css?v=2" />
    <link rel="icon" href="img/MW_logo.png" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <title>Détails d'une demande</title>
</head>
<!-- Modal de confirmation -->
<div id="confirmModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p>Êtes-vous sûr de vouloir supprimer cette demande ?</p>
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
                <h1 class="title_admin">Modifier le type : <?= htmlspecialchars($demande['name']) ?></h1>
                <form method="POST" class="form_admin">
                    <label for="nom" class="label_admin">Nom du type</label>
                    <input type="text" id="nom" name="nom" class="input_admin"
                        value="<?= htmlspecialchars($demande['name']) ?>" required>

                    <div class="button_container">
                        <button class="goBack"><a href="requestList.php">
                                < Retour</a></button>
                        <button type="button" class="btn_red" onclick="openModal()">Supprimer</button>

                        <button type="submit" name="modifier" class="btn_blue">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>