<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../users_area/login.php');
    exit();
}

// Inclure la configuration de la base de données
include('../config/db.php');
if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

// Vérifier si le formulaire a été soumis
if (isset($_POST['question']) && !empty($_POST['question'])) {
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $user_id = $_SESSION['user_id']; // Récupérer l'ID de l'utilisateur connecté

    // Préparer la requête pour insérer une nouvelle discussion
    $query = "INSERT INTO discussions (user_id, question, created_at, status) VALUES (?, ?, NOW(), 'open')";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Lier les paramètres
        mysqli_stmt_bind_param($stmt, "is", $user_id, $question);

        // Exécuter la requête
        if (mysqli_stmt_execute($stmt)) {
            // Redirection après succès
            header('Location: discussions.php?success=Discussion créée avec succès.');
            exit();
        } else {
            die("Erreur lors de l'insertion des données : " . mysqli_stmt_error($stmt));
        }

        // Fermer la requête préparée
        mysqli_stmt_close($stmt);
    } else {
        die("Erreur de préparation de la requête : " . mysqli_error($conn));
    }
} else {
    // Rediriger en cas de question vide
    header('Location: create_discussion.php?error=La question est obligatoire.');
    exit();
}
?>