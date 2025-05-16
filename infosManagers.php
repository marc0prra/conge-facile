<?php
session_start();

// Connexion à la base de données
$host = "localhost";
$dbname = "congefacile";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

// Vérifier l'utilisateur connecté
if (!isset($_SESSION['user_id'])) {
    echo "Aucun utilisateur connecté.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur et ses relations
$sql = "
    SELECT 
        p.first_name,
        p.last_name,
        p.department_id,
        p.position_id,
        p.manager_id,
        d.name AS department_name,
        pos.name AS position_name,
        u.email,
        m.first_name AS manager_first_name,
        m.last_name AS manager_last_name
    FROM user u
    INNER JOIN person p ON u.person_id = p.id
    LEFT JOIN department d ON p.department_id = d.id
    LEFT JOIN position pos ON p.position_id = pos.id
    LEFT JOIN person m ON p.manager_id = m.id
    WHERE u.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Utilisateur non trouvé.";
    exit;
}

$user = $result->fetch_assoc();

// Récupérer toutes les directions
$departments = $conn->query("SELECT id, name FROM department")->fetch_all(MYSQLI_ASSOC);

// Récupérer tous les postes
$positions = $conn->query("SELECT id, name FROM position")->fetch_all(MYSQLI_ASSOC);

// Récupérer tous les managers
$managers = $conn->query("SELECT id, first_name, last_name FROM person")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css?v=2" />
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
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly />
            </div>

            <div class="service">
                <div class="services">
                    <div class="direction">
                        <p>Direction/Service</p>
                        <select name="direction_display" disabled required>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= $dept['id'] == $user['department_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dept['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="direction" value="<?= $user['department_id'] ?>">
                    </div>

                    <div class="poste">
                        <p>Poste</p>
                        <select name="poste_display" disabled required>
                            <?php foreach ($positions as $pos): ?>
                                <option value="<?= $pos['id'] ?>" <?= $pos['id'] == $user['position_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($pos['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="poste" value="<?= $user['position_id'] ?>">
                    </div>
                </div>

                <div class="manager">
                    <p>Manager</p>
                    <select name="manager_display" disabled required>
                        <?php foreach ($managers as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= $m['id'] == $user['manager_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="manager" value="<?= $user['manager_id'] ?>">
                </div>
            </div>

            <div class="inputPswd">
                <h2>Nouveau mot de passe</h2>
                <div class="forgot">
                    <div class="forgotM">
                        <p class="color">Mot de passe actuel</p>
                        <input type="password" id="password" name="current_password">
                        <img src="img/open-eye.png" alt="Afficher" class="toggle-passwordBug" onclick="togglePassword('password', this)">
                    </div>
                </div>

                <div class="infos2">
                    <div class="forgotN">
                        <p class="color">Confirmation mot de passe</p>
                        <input type="password" id="newPassword" name="new_password">
                        <img src="img/open-eye.png" alt="Afficher" class="toggle-password" onclick="togglePassword('newPassword', this)">
                    </div>
                </div>
            </div>
        </form>

        <button class="resetPswd">
            <a href="mdp.php" class="white">Réinitialiser le mot de passe</a>
        </button>
    </div>
</div>
<script src="script.js"></script>
</body>
</html>
