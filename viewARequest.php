<!DOCTYPE html>
<html lang="fr">
  
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="Style.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />

    <link
      href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
      rel="stylesheet"
    />

    <title> Consulter une demande </title>
  </head>
  <?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "username", "password", "nom_de_la_base");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupérer les informations de l'utilisateur et de la demande
$user_id = $_SESSION['user_id'];
$sql = "SELECT r.id, rt.name AS type_conge, r.start_at, r.end_at, r.comment, r.answer_comment, r.answer, r.receipt_file, d.name AS department_name, p.first_name, p.last_name, r.created_at
        FROM request r
        JOIN request_type rt ON r.request_type_id = rt.id
        JOIN person p ON r.collaborator_id = p.id
        JOIN department d ON r.department_id = d.id
        JOIN user u ON u.person_id = p.id
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $type_conge = htmlspecialchars($row['type_conge']);
    $date_debut = htmlspecialchars($row['start_at']);
    $date_fin = htmlspecialchars($row['end_at']);
    $statut = htmlspecialchars($row['answer'] === '1' ? 'Accepté' : ($row['answer'] === '0' ? 'Refusé' : 'En attente'));
    $commentaire = htmlspecialchars($row['comment']);
    $reponse_commentaire = htmlspecialchars($row['answer_comment']);
    $justificatif = htmlspecialchars($row['receipt_file']);
    $departement = htmlspecialchars($row['department_name']);
    $collaborateur = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
    $nb_jours = (strtotime($date_fin) - strtotime($date_debut)) / (60 * 60 * 24) + 1;
} else {
    echo "Aucune demande de congé trouvée.";
    exit();
}
$stmt->close();
$conn->close();
?>
  <body>
  <div class="borderTop"></div>
  <div class="top">
    <img src="img/mentalworks.png" alt="Logo MentalWorks" />
  </div>
  <div class="middle">
    <div class="left">
      <a href="accueil.html">Accueil</a>
      <a href="nouvelle.html">Nouvelle demande</a>
      <a href="historique.html" class="active"> Historique des demandes</a>
      <div class="rod"></div>
      <a href="">Mes informations</a>
      <a href="">Mes préférences</a>
      <a href="">Déconnexion</a>
    </div>
    <div class="right">
        <h1>Demande de <?= htmlspecialchars($demande['first_name'] . ' ' . $demande['last_name']) ?></h1>
        <p> Demande du <?= htmlspecialchars($demande['date_debut']) ?> au <?= htmlspecialchars($demande['date_fin']) ?> </p>

        <div class="sectionRequestDetails">
            <p class="TypeRequest"> Type de congé : <?= htmlspecialchars($demande['type_demande']) ?></p>
            <p class="TypeRequest"> Département : <?php echo $departement; ?></p>
            <p class="TypeRequest"> Nombre de jours : <?php echo $nb_jours; ?></p>
            <p class="TypeRequest statuts"> Statut de la demande  : <?php echo $statut; ?></p> 
            <div class="statutRequest"></div>

            <div class="justificative RequestDetails">
              <p class="color">Commentaire supplémentaire</p>
              <input class="mangerComment" type="text" name="comment" value="<?php echo $commentaire; ?>">
            </div>

            <?php if (!empty($justificatif)): ?>
                <a href="uploads/<?php echo $justificatif; ?>" class="moreDetails" download>Télécharger le justificatif</a>
            <?php else: ?>
                <p>Aucun justificatif disponible.</p>
            <?php endif; ?>

            <div class="managerResponse">
                <h1>Répondre à la demande </h1>
                <div class="justificative RequestDetails">
                    <p class="color">Saisir un commentaire</p>
                    <input class="mangerComment" type="text" name="response_comment" placeholder="Votre commentaire ici">
                    <div class="end">
                        <button class="reject" type="submit">Refuser la demande</button>
                        <button class="accept" type="submit">Valider la demande</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>