<?php
// Activer les erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); // Démarrage de la session

// Inclure la connexion à la base de données
include('../config/db.php');

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Préparer la requête pour vérifier les informations
    $query = "SELECT * FROM administrators WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Associer les paramètres et exécuter la requête
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Vérifier si l'utilisateur existe
        if ($result && mysqli_num_rows($result) > 0) {
            $admin = mysqli_fetch_assoc($result);

            // Vérifier le mot de passe
            if (password_verify($password, $admin['password'])) {
                // Stocker les informations dans la session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];

                // Rediriger vers le tableau de bord
                header("Location: ../users_area/admin_dashboard.php");
                exit;
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Aucun administrateur trouvé avec cet email.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Erreur de requête : " . mysqli_error($conn);
    }
}

// Fermer la connexion
mysqli_close($conn);
?>