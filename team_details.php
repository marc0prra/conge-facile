<?php
require 'config.php';

if (!isset($_GET['id'])) {
    die("Aucune personne sélectionnée.");
}

$id = (int) $_GET['id'];

// Récupérer les infos de la personne
$stmt = $conn->prepare("
    SELECT person.*, 
           COALESCE(user.email, 'email introuvable') AS email,
           position.name AS position_name,
           department.name AS department_name
    FROM person
    LEFT JOIN user ON user.person_id = person.id
    LEFT JOIN position ON person.position_id = position.id
    LEFT JOIN department ON person.department_id = department.id
    WHERE person.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$person = $result->fetch_assoc();
$stmt->close();

if (!$person) {
    die("Collaborateur introuvable.");
}

// Récupérer les services et postes pour les listes déroulantes
$services = $conn->query("SELECT id, name FROM department")->fetch_all(MYSQLI_ASSOC);
$postes = $conn->query("SELECT id, name FROM position")->fetch_all(MYSQLI_ASSOC);

// Suppression
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["supprimer"])) {
        $stmt = $conn->prepare("DELETE FROM person WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: myTeam.php");
            exit();
        } else {
            echo "Erreur lors de la suppression.";
        }
    }

    // Modification
    if (isset($_POST["modifier"])) {
        $poste = $_POST['position_id'];
        $service = $_POST['department_id'];

        $stmt = $conn->prepare("UPDATE person SET position_id = ?, department_id = ? WHERE id = ?");
        $stmt->bind_param("iii", $poste, $service, $id);
        if ($stmt->execute()) {
            header("Location: myTeam.php");
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
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <title>Détails d'une équipe</title>
</head>

<!-- Modal de confirmation -->
<div id="confirmModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p>Êtes-vous sûr de vouloir supprimer ce collaborateur ?</p>
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
            <h1 class="title_admin">Modifier : <?= htmlspecialchars($person['first_name'] . " " . $person['last_name']) ?></h1>
            <form method="POST" class="form_admin">
                <p><strong>Email :</strong> <?= htmlspecialchars($person['email']) ?></p>

                <label for="position_id" class="label_admin">Poste</label>
                <select name="position_id" id="position_id" class="input_admin" required>
                    <?php foreach ($postes as $poste) : ?>
                        <option value="<?= $poste['id'] ?>" <?= ($poste['id'] == $person['position_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($poste['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="department_id" class="label_admin">Service</label>
                <select name="department_id" id="department_id" class="input_admin" required>
                    <?php foreach ($services as $service) : ?>
                        <option value="<?= $service['id'] ?>" <?= ($service['id'] == $person['department_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($service['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="button_container">
                    <button class="goBack"><a href="myTeam.php">< Retour</a></button>
                    <button type="button" class="btn_red" onclick="openModal()">Supprimer</button>
                    <button type="submit" name="modifier" class="btn_blue">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById("confirmModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("confirmModal").style.display = "none";
    }
</script>
</body>
</html>
