<?php 
session_start();

$managers = &$_SESSION['managers'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$manager = null;
$index = null;

foreach ($managers as $i => $m) {
    if ($m['id'] === $id) {
        $manager = $m;
        $index = $i;
        break;
    }
}

if (!$manager) {
    die("Manager introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['supprimer'])) {
        array_splice($managers, $index, 1);
        header("Location: Managers.php");
        exit;
    }

    if (isset($_POST['modifier'])) {
        $managers[$index]['nom'] = trim($_POST['nom']);
        $managers[$index]['prenom'] = trim($_POST['prenom']);
        $managers[$index]['service'] = trim($_POST['service']);
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
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <div class="container_admin">
            <h1 class="title_admin">Modifier le manager</h1>
            <form method="POST" class="form_admin">
                <label class="label_admin">Nom de famille</label>
                <input type="text" name="nom" class="input_admin" value="<?= htmlspecialchars($manager['nom']) ?>" required>

                <label class="label_admin">Prénom</label>
                <input type="text" name="prenom" class="input_admin" value="<?= htmlspecialchars($manager['prenom']) ?>" required>

                <label class="label_admin">Service</label>
                <input type="text" name="service" class="input_admin" value="<?= htmlspecialchars($manager['service']) ?>" required>

                <div class="button_container">
                    <button type="submit" name="supprimer" class="btn_red">Supprimer</button>
                    <button type="submit" name="modifier" class="btn_blue">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
