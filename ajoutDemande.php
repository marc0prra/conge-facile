<?php 
session_start();

if (!isset($_SESSION['demandes'])) {
    $_SESSION['demandes'] = [];
}

$demandes = &$_SESSION['demandes'];

if (isset($_POST['ajouter'])) {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);

    if (!empty($titre) && !empty($description)) {
        $nextId = 1;
        if (!empty($demandes)) {
            $ids = array_column($demandes, 'id');
            $nextId = max($ids) + 1;
        }

        $demandes[] = [
            'id' => $nextId,
            'titre' => $titre,
            'description' => $description
        ];

        header("Location: demande.php");
        exit;
    }
}

if (isset($_POST['annuler'])) {
    header("Location: demande.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Postes</title>
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
        <h1>Ajoutez une demande</h1>
        <div class="ajout_post">
            <form method="POST" class="form_admin">
                <label for="titre" class="label_admin">Titre de la demande</label>
                <input type="text" id="titre" name="titre" class="input_admin" required>

                <label for="description" class="label_admin">Description</label>
                <input type="text" id="description" name="description" class="input_admin" required>

                <div class="button_container">
                    <button type="submit" name="annuler" class="btn_red">Annuler</button>
                    <button type="submit" name="ajouter" class="btn_blue">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>