<?php
session_start();
require 'config.php';


$sqlDepartments = "SELECT id, name FROM department ORDER BY name ASC";
$departments = [];
$result = $conn->query($sqlDepartments);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}


if (isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $department_id = $_POST['department_id'];
    
    $manager_id_defaut = 1; 

    if (!empty($nom) && !empty($prenom) && !empty($department_id)) {
        $stmt = $conn->prepare("INSERT INTO person (first_name, last_name, department_id, manager_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $prenom, $nom, $department_id, $manager_id_defaut);

        if ($stmt->execute()) {
            header("Location: Managers.php");
            exit;
        } else {
            $error = "Erreur lors de l'ajout : " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Tous les champs sont requis.";
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
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <title>Ajout d'un manager</title>
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <h1>Ajouter un manager</h1>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <div class="ajout_post">
            <form method="POST" class="form_admin">
            <label for="nom" class="label_admin">Adresse Email - champ obligatoiree</label>
            <input type="text" id="nom" name="nom" class="input_admin" required>

                <label for="nom" class="label_admin">Nom de famille - champ obligatoiree</label>
                <input type="text" id="nom" name="nom" class="input_admin" required>

                <label for="prenom" class="label_admin">Prénom- champ obligatoire</label>
                <input type="text" id="prenom" name="prenom" class="input_admin" required>

                <label for="department_id" class="label_admin">Direction/Service - champ obligatoire</label>
                <select id="department_id" name="department_id" class="input_admin" required>
                    <option value="">Sélectionner un service</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="forgot">
                        <div class="forgotM">
                            <p for="nom" class="label_admin">Saisir un mot de passe - champ obligatoire</p>
                            <input type="password" id="password" name="current_password">
                            <img src="img/open-eye.png" alt="Afficher" class="toggle-passwordBug" onclick="togglePassword('password', this)">
                        </div>
                    </div>

                <div class="button_container">
                    <a href="Managers.php" class="btn_red">Annuler</a>
                    <button type="submit" name="ajouter" class="btn_blue">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

