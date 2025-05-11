<?php
session_start();

$host = "localhost";
$dbname = "congefacile";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    echo "Aucun utilisateur connecté.";
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT person.last_name, person.first_name, user.email
    FROM user
    INNER JOIN person ON user.person_id = person.id
    WHERE user.id = ?
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erreur lors de la préparation de la requête: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Utilisateur non trouvé.";
    exit;
}

$stmt->close();
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
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <title>Mes informations</title>
</head>

<body>
    <?php include 'include/top.php'; ?>
    <div class="middle">
        <?php include 'include/left.php'; ?>
        <div class="right">
            <h1>Mes informations</h1>

            <form action="infosC.php" method="POST">
                <div class="infos">
                    <div class="begin">
                        <p>Nom de famille</p>
                        <input type="text" name="nom" value="<?= htmlspecialchars($user['last_name']) ?>" readonly />
                    </div>
                    <div class="end">
                        <p>Prénom</p>
                        <input type="text" name="prenom" value="<?= htmlspecialchars($user['first_name']) ?>" readonly />
                    </div>
                </div>

                <div class="email">
                    <p>Adresse email</p>
                    <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>" style="
                        background-image: url('img/email.png');
                        background-size: 20px;
                        background-position: 10px center;
                        background-repeat: no-repeat;
                    " readonly />
                </div>

                <div class="service">
                    <div class="services">
                        <div class="direction">
                            <p>Direction/Service</p>
                            <select name="direction_display" disabled required>
                                <option value="1" selected>BU Symfony</option>
                                <option value="2">Mentalworks</option>
                            </select>
                            <input type="hidden" name="direction" value="1">
                        </div>

                        <div class="poste">
                            <p>Poste</p>
                            <select name="poste_display" disabled required>
                                <option value="1" selected>Directeur technique</option>
                                <option value="2">Lead developper</option>
                            </select>
                            <input type="hidden" name="poste" value="1">
                        </div>
                    </div>

                    <div class="manager">
                        <p>Manager</p>
                        <select name="manager_display" disabled required>
                            <option value="1" selected>Frédéric Salesses</option>
                            <option value="2">test test</option>
                        </select>
                        <input type="hidden" name="manager" value="1">
                    </div>
                </div>

                <div class="">
                    <h2>Réinitialiser son mot de passe</h2>

                    <div class="forgot">
                        <div class="forgotM">
                            <p class="color">Mot de passe actuel</p>
                            <input type="password" id="password" name="current_password">
                            <img src="img/open-eye.png" alt="Afficher" class="toggle-passwordBug" onclick="togglePassword('password', this)">
                        </div>
                    </div>

                    <div class="infos2">
                        <div class="forgotN">
                            <p class="color">Nouveau mot de passe</p>
                            <input type="password" id="newPassword" name="new_password">
                            <img src="img/open-eye.png" alt="Afficher" class="toggle-password" onclick="togglePassword('newPassword', this)">
                        </div>

                        <div class="forgotF">
                            <p class="color">Confirmation du mot de passe</p>
                            <input type="password" id="confirmPassword" name="confirm_password">
                            <img src="img/open-eye.png" alt="Afficher" class="toggle-password" onclick="togglePassword('confirmPassword', this)">
                        </div>
                    </div>
                </div>

            </form>

            <button class="resetPswd"><a href="mdp.php" class="white">Réinitialiser le mot de passe</a></button>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
