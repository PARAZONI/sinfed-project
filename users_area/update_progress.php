<?php
include('../config/db.php');
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];  // L'ID de l'étudiant (ou formateur)
$course_id = $_GET['course_id'];  // Récupère l'ID du cours depuis l'URL ou autre source

// Mise à jour de la progression des leçons
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $progress_lessons = $_POST['progress_lessons'];
    $progress_exercises = $_POST['progress_exercises'];
    $progress_quizzes = $_POST['progress_quizzes'];

    // Mettre à jour la progression des leçons, exercices, et quiz
    $query = "UPDATE student_courses SET 
              progress_lessons = ?, 
              progress_exercises = ?, 
              progress_quizzes = ? 
              WHERE student_id = ? AND course_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "dddis", $progress_lessons, $progress_exercises, $progress_quizzes, $student_id, $course_id);

    if (mysqli_stmt_execute($stmt)) {
        // Calculer la progression du cours
        $query = "SELECT AVG(progress_lessons) AS avg_lessons, AVG(progress_exercises) AS avg_exercises, AVG(progress_quizzes) AS avg_quizzes 
                  FROM student_courses WHERE student_id = ? AND course_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $student_id, $course_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        // Calculer la progression totale du cours
        $avg_progress_lessons = $row['avg_lessons'];
        $avg_progress_exercises = $row['avg_exercises'];
        $avg_progress_quizzes = $row['avg_quizzes'];

        // Calculer la moyenne des progressions
        $total_progress = ($avg_progress_lessons + $avg_progress_exercises + $avg_progress_quizzes) / 3;

        // Mettre à jour la progression totale du cours
        $query = "UPDATE student_courses SET progress_course = ? WHERE student_id = ? AND course_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "dii", $total_progress, $student_id, $course_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "La progression du cours a été mise à jour avec succès!";
        } else {
            echo "Erreur lors de la mise à jour de la progression du cours.";
        }
    } else {
        echo "Erreur lors de la mise à jour des progrès.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour des progrès</title>
</head>
<body>

<h1>Mise à jour des progrès pour le cours</h1>

<form method="POST" action="">
    <label for="progress_lessons">Progression des leçons (%)</label>
    <input type="number" id="progress_lessons" name="progress_lessons" min="0" max="100" required>

    <label for="progress_exercises">Progression des exercices (%)</label>
    <input type="number" id="progress_exercises" name="progress_exercises" min="0" max="100" required>

    <label for="progress_quizzes">Progression des quiz (%)</label>
    <input type="number" id="progress_quizzes" name="progress_quizzes" min="0" max="100" required>

    <button type="submit">Mettre à jour les progrès</button>
</form>

</body>
</html>