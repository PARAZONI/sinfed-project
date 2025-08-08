<?php
include('../config/db.php');
session_start();

// Vérifier si l'élève est connecté
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];  // Récupère l'ID de l'élève connecté

// Vérifie si des cours ont été sélectionnés
if (isset($_POST['courses'])) {
    $courses = $_POST['courses'];

    // Inscrire l'élève à chaque cours sélectionné
    foreach ($courses as $course_id) {
        // Vérifier si l'élève n'est pas déjà inscrit au cours
        $query_check = "SELECT * FROM student_courses WHERE student_id = ? AND course_id = ?";
        $stmt_check = mysqli_prepare($conn, $query_check);
        mysqli_stmt_bind_param($stmt_check, "ii", $student_id, $course_id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) == 0) {
            // Inscrire l'élève au cours
            $query = "INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ii", $student_id, $course_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($stmt_check);
    }

    // Rediriger vers la page de confirmation ou tableau de bord
    header("Location: dashboard.php?message=Inscription réussie");
    exit();
} else {
    echo "Aucun cours sélectionné.";
}
?>