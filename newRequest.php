<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];


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
    if (empty($_POST["request_type_id"]) || empty($_POST["start_date"]) || empty($_POST["end_date"])) {
        $error_message = "Tous les champs obligatoires doivent être remplis.";
    } else {
        $request_type_id = intval($_POST["request_type_id"]);
        $start_at = $_POST["start_date"];
        $end_at = $_POST["end_date"];
        $comment = $_POST["comment"] ?? null;
        $created_at = date("Y-m-d H:i:s");

       
        $receipt_file = null;
        if (isset($_FILES["receipt"]) && $_FILES["receipt"]["error"] == 0) {
            $target_dir = "uploads/";
            $receipt_file = $target_dir . basename($_FILES["receipt"]["name"]);
            move_uploaded_file($_FILES["receipt"]["tmp_name"], $receipt_file);
        }

       
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
        <link
            href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
            rel="stylesheet">

        <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
        <title>Nouvelle demande</title>
    </head>

    <body>
        <?php include 'include/top.php'; ?>

        <div class="middle">
            <?php include 'include/left.php'; ?>
            <div class="right">
                <h1 class="new">Effectuer une nouvelle demande</h1>
                <p class="color">Type de demande - champ obligatoire</p>

                <form action="newRequest.php" method="POST" enctype="multipart/form-data">
                    <select name="request_type_id">
                        <option value="1">Congé payé</option>
                        <option value="2">Congé sans solde</option>
                        <option value="3">Congé Maladie</option>
                        <option value="4">Congé Maternité/Paternité</option>
                        <option value="5">Autre</option>
                    </select>

                    <div class="dates">
                        <div class="begin">
                            <p class="date color">Date début - champ obligatoire</p>
                            <input type="datetime-local" name="start_date" style="
                                background-image: url(img/calendar.png);
                                background-size: 20px;
                                background-position: 10px center;
                                background-repeat: no-repeat;
                                padding-left: 35px;
                                max-width: 340px;">
                        </div>
                        <div class="end">
                            <p class="date color">Date de fin - champ obligatoire</p>
                            <input type="datetime-local" name="end_date" style="
                                background-image: url(img/calendar.png);
                                background-size: 20px;
                                background-position: 10px center;
                                background-repeat: no-repeat;
                                padding-left: 35px;
                                max-width: 340px;">                            
                        </div>
                    </div>

                    <div class="grey">
                        <p class="greyP">Nombre de jours demandés</p>
                        <input type="number" name="date" placeholder="0" readonly />
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
                        <input type="text" name="comment"
                            placeholder="Si congé exceptionnel ou sans solde, précisez votre demande.">
                    </div>

                    <div style="color:red; margin-top: 10px;">
                        <?php if (!empty($error_message)) { echo $error_message; } ?>
                    </div>

                    <div class="end">
                        <button type="submit">Soumettre ma demande*</button>
                        <p class="error">*En cas d'erreur de saisie ou de changements, vous pourrez modifier votre
                            demande tant qu'elle n'a pas été validée par le manager.</p>
                    </div>
                </form>
            </div>
        </div>


        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const startDateInput = document.querySelector('input[name="start_date"]');
            const endDateInput = document.querySelector('input[name="end_date"]');
            const daysInput = document.querySelector('input[name="date"]');

            // Liste des jours fériés en France (ajoute/modifie selon besoin)
            const holidays = [
                "2025-01-01", // Nouvel An
                "2025-04-21", // Lundi de Pâques
                "2025-05-01", // Fête du Travail
                "2025-05-08", // Victoire 1945
                "2025-05-29", // Ascension
                "2025-06-09", // Lundi de Pentecôte
                "2025-07-14", // Fête Nationale
                "2025-08-15", // Assomption
                "2025-11-01", // Toussaint
                "2025-11-11", // Armistice 1918
                "2025-12-25" // Noël
            ];

            function calculateDays() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                if (isNaN(startDate) || isNaN(endDate) || startDate > endDate) {
                    daysInput.value = 0;
                    return;
                }

                let count = 0;
                let currentDate = new Date(startDate);

                while (currentDate <= endDate) {
                    const dayOfWeek = currentDate.getDay(); // 0 = Dimanche, 6 = Samedi
                    const formattedDate = currentDate.toISOString().split('T')[0]; // Format YYYY-MM-DD

                    // Vérifier si le jour n'est pas un week-end et pas un jour férié
                    if (dayOfWeek !== 0 && dayOfWeek !== 6 && !holidays.includes(formattedDate)) {
                        count++;
                    }

                    // Passer au jour suivant
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                daysInput.value = count;
            }

            // Écouter les changements sur les inputs de dates
            startDateInput.addEventListener("change", calculateDays);
            endDateInput.addEventListener("change", calculateDays);
        });
        </script>

    </body>

</html>