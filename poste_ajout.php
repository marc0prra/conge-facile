<?php 
session_start();

$_SESSION['postes'] = $_SESSION['postes'] ?? [
    ["id" => 1, "titre" => "Développeur Web", "description" => "Création de sites et applications web."],
    ["id" => 2, "titre" => "Administrateur Réseau", "description" => "Gestion des infrastructures réseau."],
    ["id" => 3, "titre" => "Analyste Sécurité", "description" => "Protection des systèmes informatiques."],
    ["id" => 4, "titre" => "Chef de Projet IT", "description" => "Gestion de projets technologiques."],
];

$postes = &$_SESSION['postes'];
$idSelectionne = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$posteSelectionne = null;
$indexSelectionne = null;

foreach ($postes as $index => $poste) {
    if ($poste['id'] === $idSelectionne) {
        $posteSelectionne = $poste;
        $indexSelectionne = $index;
        break;
    }
}

if (!$posteSelectionne) {
    die("Erreur : Poste introuvable.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["supprimer"])) {
        array_splice($postes, $indexSelectionne, 1);
        header("Location: Poste.php");
        exit();
    }
    
    if (isset($_POST["modifier"])) {
        $postes[$indexSelectionne]['titre'] = htmlspecialchars($_POST['nom']);
        header("Location: Poste.php");
        exit();
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
                <h1 class="title_admin">Types de postes</h1>
                <form method="POST" class="form_admin">
                  <label for="nom" class="label_admin">Nom du poste</label>
                    <input type="text" id="nom" name="nom" class="input_admin"
                        value="<?= htmlspecialchars($posteSelectionne['titre']) ?>" required>

                        <label for="description" class="label_admin">Nombre de poste</label>
                          <input type="text" id="description" name="description" class="input_admin"
                            value="<?= htmlspecialchars($posteSelectionne['description']) ?>" required>

                    <div class="button_container">
                        <button type="submit" name="supprimer" class="btn_red"
                            onclick="return confirm('Voulez-vous vraiment supprimer ce poste ?')">Supprimer</button>
                        <button type="submit" name="modifier" class="btn_blue">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>