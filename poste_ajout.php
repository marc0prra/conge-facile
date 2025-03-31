<?php 
session_start();

// Simuler les postes (à remplacer par une base de données)
$postes = [
    ["id" => 1, "titre" => "Développeur Web", "description" => "Création de sites et applications web."],
    ["id" => 2, "titre" => "Administrateur Réseau", "description" => "Gestion des infrastructures réseau."],
    ["id" => 3, "titre" => "Analyste Sécurité", "description" => "Protection des systèmes informatiques."],
    ["id" => 4, "titre" => "Chef de Projet IT", "description" => "Gestion de projets technologiques."],
];

$idSelectionne = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$posteSelectionne = null;

foreach ($postes as $poste) {
    if ($poste['id'] === $idSelectionne) {
        $posteSelectionne = $poste;
        break;
    }
}

if (!$posteSelectionne) {
    die("Erreur : Poste introuvable.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["supprimer"])) {
        echo "Suppression du poste : " . htmlspecialchars($posteSelectionne['titre']);
        // Ici, on supprimerait le poste de la base de données
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Poste</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <?php include 'include/top.php'; ?>
    <div class="middle">
        <?php include 'include/left.php'; ?>
        <div class="right">
            <div class="container_admin">
                <h1 class="title_admin">Détails du Poste</h1>
                <form method="POST" class="form_admin">
                  <label for="nom" class="label_admin">Nom du poste</label>
                    <input type="text" id="nom" name="nom" class="input_admin"
                        value="<?= htmlspecialchars($posteSelectionne['titre']) ?>" required>

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