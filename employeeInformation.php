<?php
session_start();

$host = "localhost";
$dbname = "congefacile";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    echo "Aucun utilisateur connecté.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération des infos utilisateur
$sql = "
    SELECT person.last_name, person.first_name, user.email
    FROM user
    INNER JOIN person ON user.person_id = person.id
    WHERE user.id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Traitement de la réinitialisation du mot de passe
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['reset_password'])) {
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if (empty($newPassword) || empty($confirmPassword)) {
        $message = "Les champs ne doivent pas être vides.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
        $update->bind_param("si", $hashedPassword, $user_id);
        if ($update->execute()) {
            $message = "Mot de passe réinitialisé avec succès.";
        } else {
            $message = "Erreur lors de la mise à jour.";
        }
        $update->close();
    }
}

$conn->close();
?>
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
      rel="stylesheet"
    />

    <link
      href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
      rel="stylesheet"
    />

    <title>Mes informations</title>
  </head>
</html>
<body>
<?php include 'include/top.php'; ?>
<div class="middle">
    <div class="infos_middle">
        <?php include 'include/left.php'; ?>
        <div class="right">
            <h1>Mes informations</h1>

            <?php if (!empty($message)): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST" id="resetForm">
                <div class="infos">
                    <div class="begin">
                        <p>Nom</p>
                        <input type="text" value="<?= htmlspecialchars($user['last_name']) ?>" readonly>
                    </div>
                    <div class="end">
                        <p>Prénom</p>
                        <input type="text" value="<?= htmlspecialchars($user['first_name']) ?>" readonly>
                    </div>
                </div>

                <div class="email">
                    <p>Email</p>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                </div>

                <h2>Réinitialiser le mot de passe</h2>
                <div class="forgot">
                    <div class="forgotN">
                        <p>Nouveau mot de passe</p>
                        <input type="password" name="newPassword" required>
                    </div>
                    <div class="forgotF">
                        <p>Confirmer le mot de passe</p>
                        <input type="password" name="confirmPassword" required>
                    </div>
                </div>

                <div class="button_container">
                    <button type="button" class="reset-button btn_blue" onclick="openModal()">Réinitialiser le mot de passe</button>
                    <input type="hidden" name="reset_password" value="1">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal HTML -->
<div id="customModal" class="modal">
    <div class="modal-content">
        <h2>Confirmation</h2>
        <p>Êtes-vous sûr de vouloir réinitialiser votre mot de passe ?</p>
        <div class="modal-buttons">
            <button id="confirmBtn" class="btn_blue">Oui</button>
            <button id="cancelBtn" class="btn_red">Non</button>
        </div>
    </div>
</div>
</body>
</html>
