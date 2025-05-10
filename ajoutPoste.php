<?php
require 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $name = $_POST['titre']; 
    $nb_postes_dispo = $_POST['description'];  

    try {
    
        $sql = "INSERT INTO position (name, nb_postes_dispo) VALUES (:name, :nb_postes_dispo)";
      
        $stmt = $pdo->prepare($sql);
      
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':nb_postes_dispo', $nb_postes_dispo, PDO::PARAM_INT);
        
       
        $stmt->execute();
        
        
        if ($stmt->rowCount() > 0) {
            
            header('Location: poste.php');
            exit(); 
        } else {
            echo "Aucune donnée n'a été insérée.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout du poste : " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un poste</title>
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
                    <h1 class="title_admin">Ajouter un poste</h1>
                    <form method="POST" class="form_admin" action="">
                        <label for="titre" class="label_admin">Nom du poste</label>
                        <input type="text" id="titre" name="titre" class="input_admin" >

                        <div class="button_container"><button type="button" name="supprimer" class="btn_red"><a
                                    href="poste.php">Annuler</a></button>
                            <button type="submit" class="btn_blue">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>

</html>