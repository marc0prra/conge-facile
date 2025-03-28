<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="style.css?v=2" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"/>

    <link
      href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
      rel="stylesheet"/>
      <title>Ajout de Demandes</title>

<body>
  <?php include 'include/top.php'?>
  <div class='middle'>
  <?php include 'include/left_admin.php'?>
    <div class="right">
        <div class='ajout_dmd'>
            <h1>*nom du poste*</h1>
            <p>Nom du poste</p>
            <input type="text">
            <div class='button_supad'>
                <button class='supp'>Supprimer</button>
                <button class='maj'>Mettre à jour</button>
            </div>
        </div>
    </div>
</body>