<?php 
session_start();

$_SESSION['managers'] = $_SESSION['managers'] ?? [
    ["id" => 1, "titre" => "Manager IT"],
    ["id" => 2, "titre" => "Manager RH"],
    ["id" => 3, "titre" => "Manager Financier"],
];

$managers = &$_SESSION['managers'];
$idSelectionne = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$managerSelectionne = null;
$indexSelectionne = null;

foreach ($managers as $index => $manager) {
    if ($manager['id'] === $idSelectionne) {
        $managerSelectionne = $manager;
        $indexSelectionne = $index;
        break;
    }
}

if (!$managerSelectionne) {
    die("Erreur : Manager introuvable.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["supprimer"])) {
        array_splice($managers, $indexSelectionne, 1);
        header("Location: managers.php");
        exit();
    }

    if (isset($_POST["modifier"])) {
        $managers[$indexSelectionne]['titre'] = htmlspecialchars($_POST['titre']);
        header("Location: managers.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Manager</title>
    <link rel="stylesheet" href="style.css?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <div class="container_admin">
            <h1 class="title_admin">Manager</h1>
            <form method="POST" class="form_admin">
                <label for="titre" class="label_admin">Nom du manager</label>
                <input type="text" id="titre" name="titre" class="input_admin"
                       value="<?= htmlspecialchars($managerSelectionne['titre']) ?>" required>

                <div class="button_container">
                    <button type="submit" name="supprimer" class="btn_red" onclick="return confirm('Supprimer ce manager ?')">Supprimer</button>
                    <button type="submit" name="modifier" class="btn_blue">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
