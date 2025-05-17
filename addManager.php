<?php
session_start();
require 'config.php';

$erreur = "";
$success = "";

// Récupération des données pour les select
$departments = $conn->query("SELECT id, name FROM department ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
$positions = $conn->query("SELECT id, name FROM position ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
$managers = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM person ORDER BY last_name ASC")->fetch_all(MYSQLI_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $last_name     = trim($_POST['last_name']);
    $first_name    = trim($_POST['first_name']);
    $email         = trim($_POST['email']);
    $password      = $_POST['password'];
    $department_id = $_POST['department_id'] ?: null;
    $position_id   = $_POST['position_id'] ?: null;
    $manager_id    = $_POST['manager_id'] ?: null;
    $role          = $_POST['role'] ?? 'employee';

    if (empty($last_name) || empty($first_name) || empty($email) || empty($password)) {
        $erreur = "Tous les champs obligatoires doivent être remplis.";
    } else {
        // Vérifier l'unicité de l'email
        $check = $conn->prepare("SELECT COUNT(*) FROM user WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->bind_result($count);
        $check->fetch();
        $check->close();

        if ($count > 0) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            // Insertion dans person
            $stmt = $conn->prepare("INSERT INTO person (last_name, first_name, department_id, position_id, manager_id, alert_new_request, alert_on_answer, alert_before_vacation)
                VALUES (?, ?, ?, ?, ?, 1, 1, 1)");
            $stmt->bind_param("ssiii", $last_name, $first_name, $department_id, $position_id, $manager_id);
            $stmt->execute();
            $person_id = $stmt->insert_id;
            $stmt->close();

            // Insertion dans user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO user (email, password, enabled, created_at, role, person_id)
                VALUES (?, ?, 1, NOW(), ?, ?)");
            $stmt->bind_param("sssi", $email, $hash, $role, $person_id);
            $stmt->execute();
            $stmt->close();

            $success = "Utilisateur ajouté avec succès.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajouter un utilisateur</title>
    <link rel="stylesheet" href="style.css?v=2" />
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <h1>Ajouter un utilisateur</h1>

        <?php if (!empty($success)) : ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php elseif (!empty($erreur)) : ?>
            <div class="error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" class="form_admin ajout_post">
            <label>Nom - champ obligatoire</label>
            <input type="text" name="last_name" class="input_admin" required />

            <label>Prénom - champ obligatoire</label>
            <input type="text" name="first_name" class="input_admin" required />

            <label>Email - champ obligatoire</label>
            <input type="email" name="email" class="input_admin" required />

            <div class="forgotN">
            <p>Mot de passe - champ obligatoire</p>
            <input type="password" name="password" class="input_admin" required />

            <p>Mot de passe - champ obligatoire</p>
            <input type="password" name="password" class="input_admin" required />
            </div>

            <label>Direction / Service - champ obligatoire</label>
            <select name="department_id" class="input_admin">
                <option value="">Aucun</option>
                <?php foreach ($departments as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Poste</label>
            <select name="position_id" class="input_admin">
                <option value="">Aucun</option>
                <?php foreach ($positions as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Rôle</label>
            <select name="role" class="input_admin">
                <option value="employee">Collaborateur</option>
                <option value="manager">Manager</option>
            </select>

            <div class="button_container">
                <a href="myTeam.php" class="btn_red">Annuler</a>
                <button type="submit" name="ajouter" class="btn_blue">Ajouter</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
