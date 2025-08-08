<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');

// Vérifier si une notification est marquée comme lue
if (isset($_POST['notification_id'])) {
    $notification_id = $_POST['notification_id'];
    
    // Mettre à jour le statut de la notification dans la base de données (utiliser 1 pour marquer comme lue)
    $query = "UPDATE notifications SET is_read = 1 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $notification_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Rediriger vers la page précédente après la mise à jour
    header("Location: ../users_area/student_home.php");  // Remplacer par la page du tableau de bord
    exit();
}
?>