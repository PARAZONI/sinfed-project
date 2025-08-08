<?php
// Connexion à la base de données
include('../config/db.php');
session_start();

// Vérifier si l'utilisateur est connecté et s'il est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Vérifier si l'ID et l'action sont présents
if (isset($_GET['id']) && isset($_GET['action'])) {
    $formateur_id = (int)$_GET['id'];
    $action = $_GET['action'];

    // Définir l'état du formateur selon l'action
    if ($action == 'valider') {
        $status = 'validé';
    } elseif ($action == 'refuser') {
        $status = 'refusé';
    } else {
        echo "Action inconnue.";
        exit();
    }

    // Requête pour mettre à jour le statut du formateur
    $query = "UPDATE users SET status = ? WHERE id = ? AND role = 'formateur'";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $status, $formateur_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<p>Formateur " . ($status == 'validé' ? 'validé' : 'refusé') . " avec succès.</p>";
        } else {
            echo "<p>Erreur lors de la mise à jour du formateur.</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<p>Erreur dans la requête : " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>ID ou action manquants.</p>";
}

mysqli_close($conn);
?>