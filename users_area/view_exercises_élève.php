<?php
session_start();

// Vérification si l'élève est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');

// L'ID de l'élève
$user_id = $_SESSION['user_id'];

// Récupérer les cours auxquels l'élève est inscrit
$query_courses = "SELECT * FROM student_courses WHERE student_id = ?";
$stmt_courses = mysqli_prepare($conn, $query_courses);
mysqli_stmt_bind_param($stmt_courses, "i", $user_id);
mysqli_stmt_execute($stmt_courses);
$courses_result = mysqli_stmt_get_result($stmt_courses);

$exercises_by_course = [];

while ($course = mysqli_fetch_assoc($courses_result)) {
    $course_id = $course['course_id'];

    // Détails du cours
    $query_course_details = "SELECT * FROM courses WHERE course_id = ?";
    $stmt_course_details = mysqli_prepare($conn, $query_course_details);
    mysqli_stmt_bind_param($stmt_course_details, "i", $course_id);
    mysqli_stmt_execute($stmt_course_details);
    $course_details_result = mysqli_stmt_get_result($stmt_course_details);
    $course_details = mysqli_fetch_assoc($course_details_result);

    if (!$course_details) continue;

    // Exercices
    $query_exercises = "SELECT * FROM exercises WHERE course_id = ?";
    $stmt_exercises = mysqli_prepare($conn, $query_exercises);
    mysqli_stmt_bind_param($stmt_exercises, "i", $course_id);
    mysqli_stmt_execute($stmt_exercises);
    $exercises_result = mysqli_stmt_get_result($stmt_exercises);

    $exercises = [];
    while ($exercise = mysqli_fetch_assoc($exercises_result)) {
        if (!$exercise) continue;

        // Info sur la soumission
        $query_student_exercise = "SELECT status, grade, correction FROM student_exercises WHERE student_id = ? AND exercise_id = ?";
        $stmt_student_exercise = mysqli_prepare($conn, $query_student_exercise);
        mysqli_stmt_bind_param($stmt_student_exercise, "ii", $user_id, $exercise['exercise_id']);
        mysqli_stmt_execute($stmt_student_exercise);
        $student_exercise_result = mysqli_stmt_get_result($stmt_student_exercise);
        $student_exercise = mysqli_fetch_assoc($student_exercise_result);

        if ($student_exercise) {
            $exercise['status'] = $student_exercise['status'];
            $exercise['grade'] = $student_exercise['grade'];
            $exercise['correction'] = $student_exercise['correction'];
        } else {
            $exercise['status'] = 'Non traité';
            $exercise['grade'] = null;
            $exercise['correction'] = null;
        }

        $exercises[] = $exercise;

        mysqli_stmt_close($stmt_student_exercise);
    }

    if (!empty($exercises)) {
        $exercises_by_course[] = [
            'course' => $course_details,
            'exercises' => $exercises
        ];
    }

    mysqli_stmt_close($stmt_exercises);
    mysqli_stmt_close($stmt_course_details);
}

mysqli_stmt_close($stmt_courses);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Exercices</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../assets1/image_exercices.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        h1 {
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <a href="../users_area/student_home.php" class="btn">
        <i class="fas fa-arrow-left"></i> Retour
    </a>

    <h1 class="text-center mb-4">Liste des exercices</h1>

    <?php if (count($exercises_by_course) > 0): ?>
        <?php foreach ($exercises_by_course as $course_data): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h3><?php echo htmlspecialchars($course_data['course']['title'] ?? 'Cours inconnu'); ?></h3>
                    <p><?php echo htmlspecialchars($course_data['course']['description'] ?? ''); ?></p>
                </div>
                <div class="card-body">
                    <h5>Exercices :</h5>
                    <div class="list-group">
                        <?php foreach ($course_data['exercises'] as $exercise): ?>
                            <div class="list-group-item">
                                <h5><?php echo htmlspecialchars($exercise['title'] ?? 'Exercice inconnu'); ?></h5>
                                <p><?php echo htmlspecialchars($exercise['description'] ?? ''); ?></p>

                                <?php if (!empty($exercise['file_path'])): ?>
                                    <p><strong>Fichier :</strong> 
                                        <a href="<?php echo htmlspecialchars($exercise['file_path']); ?>" target="_blank">Télécharger le fichier</a>
                                    </p>
                                <?php endif; ?>

                                <?php if (strtolower($exercise['status']) == 'corrected' && !empty($exercise['grade']) && !empty($exercise['correction'])): ?>
                                    <a href="view_correction.php?exercise_id=<?php echo $exercise['exercise_id']; ?>" class="btn btn-success">
                                        Voir la correction (Note : <?php echo $exercise['grade']; ?>/20)
                                    </a>
                                <?php else: ?>
                                    <a href="treat_exercise.php?exercise_id=<?php echo $exercise['exercise_id']; ?>" class="btn btn-primary">
                                        Traiter cet exercice
                                    </a>
                                <?php endif; ?>

                                <?php if (strtolower($exercise['status']) == 'en attente de correction'): ?>
                                    <p class="mt-2 text-warning">
                                        <img src="../assets/gif/attente.gif" alt="En attente" style="width: 30px;">
                                        En attente de correction.
                                    </p>
                                <?php elseif (strtolower($exercise['status']) == 'non traité'): ?>
                                    <p class="mt-2 text-secondary">
                                        <img src="../assets/gif/non_traite.gif" alt="" style="width: 30px;">
                                        Exercice non encore traité.
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun exercice disponible pour vos cours.</p>
    <?php endif; ?>
</div>
</body>
</html>