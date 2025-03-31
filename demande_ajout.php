<?php 
session_start();

// Simuler les types de demandes (à remplacer par une base de données)
$typesDemandes = [
    ["type" => "Congé sans solde", "nb" => 400],
    ["type" => "Congé payé", "nb" => 1000],
    ["type" => "Congé maladie", "nb" => 750],
    ["type" => "Congé maternité/paternité", "nb" => 100],
    ["type" => "Autre", "nb" => 200],
];

$typeSelectionne = $_GET['type'] ?? '';

$demandeSelectionnee = null;
foreach ($typesDemandes as $demande) {
    if ($demande['type'] === $typeSelectionne) {
        $demandeSelectionnee = $demande;
        break;
    }
}

if (!$demandeSelectionnee) {
    die("Erreur : Type de demande introuvable.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["modifier"])) {
        $nouveauNom = $_POST["nom"];
        echo "Modification en cours pour : " . htmlspecialchars($nouveauNom);
        // Code pour mettre à jour la base de données
    }

    if (isset($_POST["supprimer"])) {
        echo "Suppression de la demande : " . htmlspecialchars($typeSelectionne);
        // Code pour supprimer la demande de la base de données
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la demande</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <?php include 'include/top.php'; ?>
    <div class="middle">
        <?php include 'include/left.php'; ?>
        <div class="right">
            <div class="container_admin">
                <h1 class="title_admin">Types de demandes</h1>

                <form method="POST" class="form_admin">
                    <label for="nom" class="label_admin">Nom du type</label>
                    <input type="text" id="nom" name="nom" class="input_admin"
                        value="<?= htmlspecialchars($demandeSelectionnee['type']) ?>" required>

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
