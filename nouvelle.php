<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die("Utilisateur non connecté. <a href='connexion.php'>Se connecter</a>");
}

include 'config.php';

$user_id = $_SESSION['user_id'];

// Récupérer le department_id de l'utilisateur
$query = "SELECT department_id FROM person WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$stmt->close();

if (!$userData) {
    die("Erreur : impossible de récupérer le département.");
}

$department_id = $userData["department_id"];
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier que les champs ne sont pas vides
    if (empty($_POST["request_type_id"]) || empty($_POST["start_date"]) || empty($_POST["end_date"])) {
        $error_message = "Tous les champs obligatoires doivent être remplis.";
    } else {
        $request_type_id = intval($_POST["request_type_id"]);
        $start_at = $_POST["start_date"];
        $end_at = $_POST["end_date"];
        $comment = $_POST["comment"] ?? null;
        $created_at = date("Y-m-d H:i:s");

        // Gestion du fichier justificatif
        $receipt_file = null;
        if (isset($_FILES["receipt"]) && $_FILES["receipt"]["error"] == 0) {
            $target_dir = "uploads/";
            $receipt_file = $target_dir . basename($_FILES["receipt"]["name"]);
            move_uploaded_file($_FILES["receipt"]["tmp_name"], $receipt_file);
        }

        // Requête SQL avec ajout de `department_id`
        $sql = "INSERT INTO request (request_type_id, collaborator_id, department_id, created_at, start_at, end_at, receipt_file, comment, answer) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("iiisssss", $request_type_id, $user_id, $department_id, $created_at, $start_at, $end_at, $receipt_file, $comment);

            if ($stmt->execute()) {
                echo "Demande enregistrée avec succès !";
            } else {
                echo "Erreur lors de l'insertion : " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Erreur de préparation de la requête : " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=2">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
    <title>Nouvelle demande</title>
</head>

<body>
<?php include 'top.php'; ?>

    <div class="middle">
        <?php include 'left.php'; ?>
        <div class="right">
            <h1 class="new">Effectuer une nouvelle demande</h1>
            <p class="color">Type de demande - champ obligatoire</p>
            
            <form action="nouvelle.php" method="POST" enctype="multipart/form-data">
                <select name="request_type_id">
                    <option value="1">Congé payé</option>
                    <option value="2">Congé sans solde</option>
                </select>

                <div class="dates">
                    <div class="begin">
                        <p class="date color">Date début - champ obligatoire</p>
                        <input type="date" name="start_date" style="
                            background-image: url('img/calendar.png');
                            background-size: 20px;
                            background-position: 10px center;
                            background-repeat: no-repeat;">
                    </div>
                    <div class="end">
                        <p class="date color">Date de fin - champ obligatoire</p>
                        <input type="date" name="end_date" style="
                            background-image: url('img/calendar.png');
                            background-size: 20px;
                            background-position: 10px center;
                            background-repeat: no-repeat;">
                    </div>
                </div>

                <div class="grey">
                    <p class="greyP">Nombre de jours demandés</p>
                    <input type="number" name="date" placeholder="0">
                </div>

                <div class="files">
                    <p class="color">Justificatif si applicable</p>
                    <label for="file-upload" class="custom-file-upload">
                        <img src="img/document.png" alt="Icone document"> Sélectionner un fichier
                    </label>
                    <input type="file" id="file-upload" name="receipt">
                </div>

                <div class="justificative">
                    <p class="color">Commentaire supplémentaire</p>
                    <input type="text" name="comment" placeholder="Si congé exceptionnel ou sans solde, précisez votre demande.">
                </div>

                <div style="color:red; margin-top: 10px;">
                    <?php if (!empty($error_message)) { echo $error_message; } ?>
                </div>

                <div class="end">
                    <button type="submit">Soumettre ma demande*</button>
                    <p class="error">*En cas d'erreur de saisie ou de changements, vous pourrez modifier votre demande tant qu'elle n'a pas été validée par le manager.</p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
