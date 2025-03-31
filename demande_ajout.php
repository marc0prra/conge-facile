<?php  
session_start();



if (!isset($_SESSION['demandes'])) {
    $_SESSION['demandes'] = [
        ["id" => 1, "titre" => "Demande de congé", "description" => "Demande de congé annuel."],
        ["id" => 2, "titre" => "Demande de matériel", "description" => "Commande de nouveau matériel informatique."],
        ["id" => 3, "titre" => "Demande de formation", "description" => "Inscription à une formation professionnelle."],
        ["id" => 4, "titre" => "Demande de remboursement", "description" => "Remboursement des frais professionnels."],
    ];
}

$demandes = &$_SESSION['demandes'];
$idSelectionne = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$demandeSelectionnee = null;
$indexSelectionne = null;

foreach ($demandes as $index => $demande) {
    if ($demande['id'] === $idSelectionne) {
        $demandeSelectionnee = $demande;
        $indexSelectionne = $index;
        break;
    }
}

if (!$demandeSelectionnee) {
    die("Erreur : Type de demande introuvable.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["supprimer"])) {
        array_splice($demandes, $indexSelectionne, 1);
        header("Location: demande.php");
        exit();
    }
    
    if (isset($_POST["modifier"])) {
        $demandes[$indexSelectionne]['titre'] = htmlspecialchars($_POST['nom']);
        $demandes[$indexSelectionne]['description'] = htmlspecialchars($_POST['description']);
        header("Location: demande.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Détails de la Demande</title>
        <link rel="stylesheet" href="style.css?v=2">
    </head>

    <body>
        <?php include 'include/top.php'; ?>
        <div class="middle">
            <?php include 'include/left.php'; ?>
            <div class="right">
                <div class="container_admin">
                    <h1 class="title_admin">Types de la Demande</h1>
                    <form method="POST" class="form_admin">
                        <label for="nom" class="label_admin">Nom du type</label>
                        <input type="text" id="nom" name="nom" class="input_admin"
                            value="<?= htmlspecialchars($demandeSelectionnee['titre']) ?>" required>

                        <label for="description" class="label_admin">Nombre de types de congés</label>
                        <input type="text" id="description" name="description" class="input_admin"
                            value="<?= htmlspecialchars($demandeSelectionnee['description']) ?>" required>

                        <div class="button_container">
                            <button type="submit" name="supprimer" class="btn_red"
                                onclick="return confirm('Voulez-vous vraiment supprimer cette demande ?')">Supprimer</button>
                            <button type="submit" name="modifier" class="btn_blue">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>

</html>