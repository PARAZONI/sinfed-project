<?php
// Activer les erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Démarrer la session
session_start();

// Connexion à la base de données
include('../config/db.php');

// Définir une variable pour les messages d'erreur
$error_message = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Rechercher l'administrateur dans la base de données
    $query = "SELECT * FROM administrators WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Vérifier le mot de passe
        if (password_verify($password, $row['password'])) {
            // Connexion réussie, initialiser la session et rediriger
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_email'] = $row['email'];

            // Rediriger vers la page de gestion des formateurs
            header('Location: ../users_area/manage_formateurs.php');
            exit();
        } else {
            $error_message = "Mot de passe incorrect.";
        }
    } else {
        $error_message = "Email non trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur</title>
    <link rel="stylesheet" href="../styles/style.css"> <!-- Assurez-vous d'avoir un fichier CSS approprié -->
</head>
<body>
    <h2>Connexion Administrateur</h2>

    <!-- Affichage des erreurs si existantes -->
    <?php if (!empty($error_message)): ?>
        <div style="color: red;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form action="process_admin_login.php" method="POST">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" placeholder="Votre email" required>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
        
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>