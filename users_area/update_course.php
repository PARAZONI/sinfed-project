<?php
include('../config/db.php');

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données du formulaire
    $course_id = $_POST['course_id'] ?? null;
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;

    // Vérifie que les champs obligatoires sont remplis
    if ($course_id && $title && $description && $start_date) {
        // Prépare la requête SQL pour mettre à jour le cours
        $query = "UPDATE courses SET title = ?, description = ?, start_date = ?, end_date = ? WHERE course_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $title, $description, $start_date, $end_date, $course_id);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            echo "Le cours a été mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du cours : " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Veuillez remplir tous les champs obligatoires.";
    }
} else {
    echo "Méthode non autorisée.";
}
?>