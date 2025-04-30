<?php 
session_start();

$_SESSION['directions'] = $_SESSION['directions'] ?? [
    ["id" => 1, "titre" => "Direction Informatique", "description" ],
    ["id" => 2, "titre" => "Direction RH", "description" ],
    ["id" => 3, "titre" => "Direction Financière", "description"],
];

$directions = &$_SESSION['directions'];
$idSelectionne = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$directionSelectionnee = null;
$indexSelectionne = null;

foreach ($directions as $index => $dir) {
    if ($dir['id'] === $idSelectionne) {
        $directionSelectionnee = $dir;
        $indexSelectionne = $index;
        break;
    }
}

if (!$directionSelectionnee) {
    die("Erreur : Direction introuvable.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["supprimer"])) {
        array_splice($directions, $indexSelectionne, 1);
        header("Location: direction.php");
        exit();
    }

    if (isset($_POST["modifier"])) {
        $directions[$indexSelectionne]['titre'] = htmlspecialchars($_POST['titre']);
        $directions[$indexSelectionne]['description'] = htmlspecialchars($_POST['description']);
        header("Location: direction.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails des Directions</title>
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
            <h1 class="title_admin">Direction / Service</h1>
            <form method="POST" class="form_admin">
                <label for="titre" class="label_admin">Nom de la direction</label>
                <input type="text" id="titre" name="titre" class="input_admin"
                       value="<?= htmlspecialchars($directionSelectionnee['titre']) ?>" required>

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