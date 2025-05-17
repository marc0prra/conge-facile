<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['user_role'] ?? '1';
$user_prenom = 'Utilisateur';
$user_nom = '';
$person_id = null;

if (empty($_SESSION['user_id']) && $_SERVER['PHP_SELF'] !== 'connexion.php') {
    header("Location: connexion.php");
    exit();
}

if ($user_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT person.id AS person_id, person.last_name, person.first_name, user.email
            FROM user
            INNER JOIN person ON user.person_id = person.id
            WHERE user.id = :id
        ");
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user_prenom = !empty($user['first_name']) ? htmlspecialchars($user['first_name']) : "";
            $user_nom = !empty($user['last_name']) ? htmlspecialchars($user['last_name']) : "";
            $user_email = !empty($user['email']) ? htmlspecialchars($user['email']) : "";
        }
    } catch (PDOException $e) {
        $user_prenom = 'Erreur';
    }
}


try {
    if ($user_role === '1') {
        // Collaborateur : ses propres demandes
        $sql = "SELECT COUNT(*) FROM request WHERE answer = 0 AND collaborator_id = ?";
        $params = [$person_id];

    } elseif ($user_role === '2') {
        $sql = "
            SELECT COUNT(*)
            FROM request r
            JOIN person p ON r.collaborator_id = p.id
            WHERE r.answer = 0 AND p.manager_id = ?
        ";
        $params = [$person_id];

    } else {
        $sql = "SELECT COUNT(*) FROM request WHERE answer = 0";
        $params = [];
    }

    $stmtCount = $pdo->prepare($sql);
    $stmtCount->execute($params);
    $nombreDemandes = $stmtCount->fetchColumn();

    if ($nombreDemandes > 9) {
        $nombreDemandes = '9+';
    }

} catch (PDOException $e) {
    $nombreDemandes = 'E';
}
?>