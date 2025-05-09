<?php
require 'config.php'; // Connexion à la base de données

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
            header("Location: demande.php");
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
            header("Location: demande.php");
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Demande</title>
    <link rel="stylesheet" href="style.css?v=2">
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

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
}

.modal-content {
    background-color: #fff;
    padding: 2rem;
    border-radius: 10px;
    text-align: center;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.modal-buttons {
    display: flex;
    justify-content: space-around;
    margin-top: 1.5rem;
}
</style>

<script>
function openModal() {
    document.getElementById('confirmModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('confirmModal').style.display = 'none';
}
</script>

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
                    <button type="button" class="btn_red" onclick="openModal()">Supprimer</button>

                        <button type="submit" name="modifier" class="btn_blue">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
