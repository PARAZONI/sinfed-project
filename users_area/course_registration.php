<?php
include('../config/db.php');
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['student_id'])) {
    echo "Veuillez vous connecter pour vous inscrire à un cours.";
    exit();
}

// Récupère l'ID du formateur ou du cours à inscrire
if (!isset($_GET['course_id']) || empty($_GET['course_id'])) {
    echo "Cours introuvable.";
    exit();
}

$course_id = intval($_GET['course_id']);

// Préparer la requête pour récupérer les détails du cours
$query = "SELECT * FROM courses WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$course_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($course_result) > 0) {
    $course = mysqli_fetch_assoc($course_result);
    echo "<h1>Inscription au cours : " . htmlspecialchars($course['title']) . "</h1>";
    echo "<p>Description : " . htmlspecialchars($course['description']) . "</p>";
    echo "<p>Date de début : " . htmlspecialchars($course['start_date']) . "</p>";
} else {
    echo "Le cours n'existe pas.";
    exit();
}

mysqli_stmt_close($stmt);

// Gérer l'inscription de l'élève
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifie si le cours est déjà inscrit
    $student_id = $_SESSION['student_id'];
    $check_query = "SELECT * FROM course_registrations WHERE course_id = ? AND student_id = ?";
    $stmt_check = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt_check, "ii", $course_id, $student_id);
    mysqli_stmt_execute($stmt_check);
    $check_result = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($check_result) > 0) {
        echo "Vous êtes déjà inscrit à ce cours.";
    } else {
        // Inscription de l'élève
        $insert_query = "INSERT INTO course_registrations (course_id, student_id) VALUES (?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt_insert, "ii", $course_id, $student_id);

        if (mysqli_stmt_execute($stmt_insert)) {
            echo "Inscription réussie au cours!";
            // Redirection après inscription
            header("Location: my_courses.php");
            exit();
        } else {
            echo "Erreur lors de l'inscription.";
        }
        mysqli_stmt_close($stmt_insert);
    }
    mysqli_stmt_close($stmt_check);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

</head>
<body>
   <!-- Formulaire d'inscription -->
<form action="" method="POST">
    <button type="submit">S'inscrire à ce cours</button>
</form> 
</body>
</html>
