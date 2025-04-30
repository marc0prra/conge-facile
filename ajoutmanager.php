<?php 
session_start();

if (!isset($_SESSION['managers'])) {
    $_SESSION['managers'] = [];
}

$managers = &$_SESSION['managers'];

if (isset($_POST['modifier'])) {
    $nom = trim($_POST['nom']);

    if (!empty($nom)) {
        $nextId = 1;
        if (!empty($managers)) {
            $ids = array_column($managers, 'id');
            $nextId = max($ids) + 1;
        }

        $managers[] = [
            'id' => $nextId,
            'titre' => $nom
        ];

        header("Location: managers.php");
        exit;
    }
}

if (isset($_POST['supprimer'])) {
    header("Location: managers.php");
    exit;
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
    <title>Ajouter un manager</title>
</head>

<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <?php include 'include/left.php'; ?>
    <div class="right">
        <h1>Ajoutez un manager</h1>
        <div class="ajout_post">
            <form method="POST" class="form_admin">
                <label for="nom" class="label_admin">Nom du manager</label>
                <input type="text" id="nom" name="nom" class="input_admin" required>

                <div class="button_container">
                    <button type="button" class="btn_red"><a href="managers.php">Annuler</a></button>
                    <button type="submit" name="modifier" class="btn_blue">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
