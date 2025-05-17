<?php
require_once("include/config_bdd.php");
require_once("include/user.php");

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Récupérer le manager depuis la base de données
$sql = "SELECT * FROM person WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$manager = $result->fetch_assoc();

if (!$manager) {
    die("Manager introuvable.");
}

// Récupération des services
$sqlDepartments = "SELECT id, name FROM department ORDER BY name ASC";
$departments = [];
$result = $conn->query($sqlDepartments);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['supprimer'])) {
        $deleteStmt = $conn->prepare("DELETE FROM person WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        $deleteStmt->execute();
        header("Location: Managers.php");
        exit;
    }

    if (isset($_POST['modifier'])) {
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $department_id = (int) $_POST['department_id'];

        $updateStmt = $conn->prepare("UPDATE person SET last_name = ?, first_name = ?, department_id = ? WHERE id = ?");
        $updateStmt->bind_param("ssii", $nom, $prenom, $department_id, $id);
        $updateStmt->execute();
        header("Location: Managers.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un Manager</title>
    <link rel="stylesheet" href="style.css?v=2" />
    <script>
        function openModal() {
            document.getElementById('confirmModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }
    </script>
</head>

<body>
    <!-- Modal de confirmation -->
    <div id="confirmModal" class="modal" style="display: none;">
        <div class="modal-content">
            <p>Êtes-vous sûr de vouloir supprimer ce manager ?</p>
            <div class="modal-buttons">
                <form method="POST">
                    <input type="hidden" name="supprimer" value="1">
                    <button type="submit" class="btn_red">Oui, supprimer</button>
                </form>
                <button onclick="closeModal()" class="btn_blue">Annuler</button>
            </div>
        </div>
    </div>

    <?php include 'include/top.php'; ?>
    <div class="middle">
        <?php include 'include/left.php'; ?>
        <div class="right">
            <div class="container_admin">
                <h1 class="title_admin">Modifier le manager</h1>
                <form method="POST" class="form_admin">
                    <label class="label_admin">Nom de famille</label>
                    <input type="text" name="nom" class="input_admin"
                        value="<?= htmlspecialchars($manager['last_name']) ?>" required>

                    <label class="label_admin">Prénom</label>
                    <input type="text" name="prenom" class="input_admin"
                        value="<?= htmlspecialchars($manager['first_name']) ?>" required>

                    <label class="label_admin">Service</label>
                    <select name="department_id" class="input_admin" required>
                        <option value="">-- Sélectionner un service --</option>
                        <?php foreach ($departments as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= $d['id'] == $manager['department_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="button_container">
                        <button class="goBack"><a href="myTeam.php">&lt; Retour</a></button>
                        <button type="button" class="btn_red" onclick="openModal()">Supprimer</button>
                        <button type="submit" name="modifier" class="btn_blue">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>