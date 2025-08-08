<?php
// Activer les erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Démarrer la session
session_start();

// Connexion à la base de données
include('../config/db.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = 'admin'; // Définir le rôle

    // Vérifier si les champs sont vides
    if (empty($username) || empty($email) || empty($password)) {
        echo "<p style='color: red;'>Tous les champs sont requis.</p>";
    } else {
        // Vérifier si l'email existe déjà
        $check_query = "SELECT id FROM administrators WHERE email = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            // Si l'email existe déjà
            echo "<p style='color: red;'>Cet email est déjà enregistré. Veuillez utiliser un autre email.</p>";
        } else {
            // Hacher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insérer dans la base de données
            $query = "INSERT INTO administrators (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashed_password, $role);
                if (mysqli_stmt_execute($stmt)) {
                    // Rediriger vers la page de connexion
                    header("Location: login_admin.php");
                    exit();
                } else {
                    echo "<p style='color: red;'>Erreur SQL : " . mysqli_error($conn) . "</p>";
                }
            }
        }

        mysqli_stmt_close($check_stmt);
    }
}

// Fermer la connexion
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Administrateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Inscription Administrateur</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        <p class="mt-3">Déjà un compte ? <a href="../users_area/admin_login.php">Connectez-vous ici</a>.</p>
    </div>
</body>
</html>