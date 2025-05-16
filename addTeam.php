<?php
require 'config.php'; 

$erreur = "";
$success = "";

$departments = $pdo->query("SELECT id, name FROM department")->fetchAll();
$positions   = $pdo->query("SELECT id, name FROM position")->fetchAll();
$managers    = $pdo->query("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM person")->fetchAll();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $last_name     = trim($_POST['last_name'] ?? '');
    $first_name    = trim($_POST['first_name'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $password      = $_POST['password'] ?? '';
    $department_id = $_POST['department_id'] ?? null;
    $position_id   = $_POST['position_id'] ?? null;
    $manager_id    = $_POST['manager_id'] ?: null;
    $role          = $_POST['role'] ?? 'employee';

    if (empty($last_name) || empty($first_name) || empty($email) || empty($password)) {
        $erreur = "Tous les champs obligatoires doivent être remplis.";
    } else {
        $check = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
        $check->execute([':email' => $email]);
        if ($check->fetchColumn() > 0) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO person (last_name, first_name, department_id, position_id, manager_id, alert_new_request, alert_on_answer, alert_before_vacation)
                VALUES (:last_name, :first_name, :department_id, :position_id, :manager_id, 1, 1, 1)
            ");
            $stmt->execute([
                ':last_name'     => $last_name,
                ':first_name'    => $first_name,
                ':department_id' => $department_id,
                ':position_id'   => $position_id,
                ':manager_id'    => $manager_id ?: null,
            ]);

            $person_id = $pdo->lastInsertId();

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO user (email, password, enabled, created_at, role, person_id)
                VALUES (:email, :password, 1, NOW(), :role, :person_id)
            ");
            $stmt->execute([
                ':email'     => $email,
                ':password'  => $hash,
                ':role'      => $role,
                ':person_id' => $person_id,
            ]);

            $success = "Collaborateur ajouté avec succès.";
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
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;900&family=Inter:wght@100;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <title>Ajouter un collaborateur</title>
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <h1>Ajouter un collaborateur</h1>

        <?php if (!empty($success)) : ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" class="form_admin ajout_post">
            <label>Nom *</label>
            <input type="text" name="last_name" class="input_admin" required />

            <label>Prénom *</label>
            <input type="text" name="first_name" class="input_admin" required />

            <label>Email *</label>
            <input type="email" name="email" class="input_admin" required />

            <label>Mot de passe *</label>
            <input type="password" name="password" class="input_admin" required />

            <label>Direction</label>
            <select name="department_id" class="input_admin">
                <?php foreach ($departments as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Poste</label>
            <select name="position_id" class="input_admin">
                <?php foreach ($positions as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Manager</label>
            <select name="manager_id" class="input_admin">
                <option value="">Aucun</option>
                <?php foreach ($managers as $m): ?>
                    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['full_name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Rôle</label>
            <select name="role" class="input_admin">
                <option value="employee">Collaborateur</option>
                <option value="manager">Manager</option>
            </select>

            <?php if (!empty($erreur)) : ?>
                <div class="error"><?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>

            <div class="button_container">
                <a href="myTeam.php" class="btn_red">Annuler</a>
                <button type="submit" name="ajouter" class="btn_blue">Ajouter</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
