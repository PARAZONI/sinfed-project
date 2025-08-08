<?php
include('../config/db.php');

if (isset($_GET['id'])) {
    // Récupère l'ID du cours à supprimer
    $course_id = $_GET['id'];

    // Prépare la requête SQL pour supprimer le cours
    $query = "DELETE FROM courses WHERE course_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        echo "Le cours a été supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression du cours : " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Aucun cours spécifié pour la suppression.";
}
?>