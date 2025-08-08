<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Connexion à la base de données
include('../config/db.php');
if (!$conn) {
    die("Erreur de connexion à la base de données");
}

// Vérifier si le formulaire a bien été envoyé
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Récupérer les données du formulaire et les sécuriser
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Préparer la requête pour éviter l'injection SQL
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Lier les paramètres
        mysqli_stmt_bind_param($stmt, "s", $email);
        // Exécuter la requête
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Récupérer les informations de l'utilisateur
            $user = mysqli_fetch_assoc($result);

            // Vérifier le mot de passe (en utilisant password_verify si le mot de passe est hashé)
            if (password_verify($password, $user['password'])) {
                // Authentification réussie
                $_SESSION['user_email'] = $email; // Stocke l'email dans la session
                $_SESSION['user_id'] = $user['id']; // Stocke l'ID dans la session
                session_regenerate_id(true); // Renouvelle l'ID de session pour plus de sécurité
                header('Location: ../users_area/discussions.php'); // Redirige vers la page des discussions
                exit();
            } else {
                // Mot de passe incorrect
                header('Location: login.php?error=invalid_credentials');
                exit();
            }
        } else {
            // Utilisateur non trouvé
            header('Location: login.php?error=invalid_credentials');
            exit();
        }

        // Fermer la requête préparée
        mysqli_stmt_close($stmt);
    } else {
        // Erreur lors de la préparation de la requête
        header('Location: login.php?error=query_error');
        exit();
    }
} else {
    // Données du formulaire manquantes
    header('Location: login.php?error=missing_fields');
    exit();
}
?>