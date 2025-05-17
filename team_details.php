<?php
require_once("include/config_bdd.php");
require_once("include/user.php");

if (!isset($_GET['id'])) {
    die("Aucun collaborateur sélectionné.");
}

$id = (int) $_GET['id'];

// Récupérer les infos du collaborateur avec date de création
$stmt = $conn->prepare("
    SELECT person.*, 
           COALESCE(user.email, '') AS email, 
           user.enabled, 
           user.id AS user_id,
           user.created_at
    FROM person 
    LEFT JOIN user ON user.person_id = person.id 
    WHERE person.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$collab = $result->fetch_assoc();
$stmt->close();

if (!$collab) {
    die("Collaborateur introuvable.");
}

// Dropdown data
$departments = $conn->query("SELECT id, name FROM department")->fetch_all(MYSQLI_ASSOC);
$positions = $conn->query("SELECT id, name FROM position")->fetch_all(MYSQLI_ASSOC);
$managers = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM person")->fetch_all(MYSQLI_ASSOC);

// Traitement du formulaire
$message = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["update"])) {
        $email = $_POST['email'];
        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $department_id = $_POST['department_id'];
        $position_id = $_POST['position_id'];
        $manager_id = $_POST['manager_id'] ?: null;
        $enabled = isset($_POST['enabled']) ? 1 : 0;

        // Met à jour la personne
        $stmt = $conn->prepare("UPDATE person SET last_name=?, first_name=?, department_id=?, position_id=?, manager_id=? WHERE id=?");
        $stmt->bind_param("ssiiii", $last_name, $first_name, $department_id, $position_id, $manager_id, $id);
        $stmt->execute();
        $stmt->close();

        // Met à jour l'utilisateur lié
        if (!empty($collab['user_id'])) {
            $stmt = $conn->prepare("UPDATE user SET email=?, enabled=? WHERE person_id=?");
            $stmt->bind_param("sii", $email, $enabled, $id);
            $stmt->execute();
            $stmt->close();

            $newPassword = $_POST['newPassword'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            if (!empty($newPassword) || !empty($confirmPassword)) {
                if ($newPassword === $confirmPassword) {
                    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE user SET password=? WHERE person_id=?");
                    $stmt->bind_param("si", $hashed, $id);
                    $stmt->execute();
                    $stmt->close();
                    $message = "Mot de passe réinitialisé avec succès.";
                } else {
                    $message = "Les mots de passe ne correspondent pas.";
                }
            }
        }

        if (empty($message)) {
            $message = "Mise à jour effectuée avec succès.";
        }
    }

    if (isset($_POST['delete'])) {
        $conn->query("DELETE FROM person WHERE id = $id");
        header("Location: myTeam.php");
        exit();
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
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;900&family=Inter:wght@100;900&display=swap"
        rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <title>Détails collaborateur</title>
</head>
<!-- Modal de confirmation -->
<div id="confirmModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p>Êtes-vous sûr de vouloir supprimer ce membre de votre équipe ?</p>
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
            <form method="POST" class="form_admin">
                <h1 class="title_admin">
                    <?= htmlspecialchars($collab['first_name'] . " " . $collab['last_name']) ?>
                </h1>

                <?php if (!empty($message)): ?>
                    <div class="message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <label class="toggle-label">
                    <input type="checkbox" name="enabled" class="switch" <?= $collab['enabled'] ? 'checked' : '' ?>>
                    <?php if (!empty($collab['created_at'])): ?>
                        <p>
                            Profil depuis le <?= date('d/m/Y', strtotime($collab['created_at'])) ?>
                        </p>
                    <?php endif; ?>
                </label>

                <div class="email">
                    <p>Adresse email - champ obligatoire</p>
                    <input type="email" name="email" value="<?= htmlspecialchars($collab['email']) ?>" required style="
                        background-image: url('img/email.png');
                        background-size: 20px;
                        background-position: 10px center;
                        background-repeat: no-repeat;" />
                </div>

                <div class="infos">
                    <div class="begin">
                        <p>Nom de famille - champ obligatoire</p>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($collab['last_name']) ?>"
                            required />
                    </div>
                    <div class="end">
                        <p>Prénom - champ obligatoire</p>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($collab['first_name']) ?>"
                            required />
                    </div>
                </div>

                <div class="services">
                    <div class="direction">
                        <p>Direction/Service - champ obligatoire</p>
                        <select name="department_id">
                            <?php foreach ($departments as $d): ?>
                                <option value="<?= $d['id'] ?>" <?= $collab['department_id'] == $d['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="poste">
                        <p>Poste - champ obligatoire</p>
                        <select name="position_id">
                            <?php foreach ($positions as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= $collab['position_id'] == $p['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="manager">
                    <p>Manager - champ obligatoire</p>
                    <select name="manager_id">
                        <?php foreach ($managers as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= $collab['manager_id'] == $m['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['full_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="infos2">
                    <div class="forgotN">
                        <p>Nouveau mot de passe</p>
                        <input type="password" name="newPassword" />
                    </div>
                    <div class="forgotF">
                        <p>Confirmation du mot de passe</p>
                        <input type="password" name="confirmPassword" />
                    </div>
                </div>

                <div class="button_container">
                    <button class="goBack"><a href="Managers.php">&lt; Retour</a></button>
                    <button type="button" class="btn_red" onclick="openModal()">Supprimer</button>
                    <button type="submit" name="update" class="btn_blue">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>