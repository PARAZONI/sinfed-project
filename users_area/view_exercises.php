<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Vérifier si un 'course_id' a été passé via l'URL
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Récupérer les détails du cours
    $course_query = "SELECT * FROM courses WHERE course_id = ?";
    $course_stmt = mysqli_prepare($conn, $course_query);
    mysqli_stmt_bind_param($course_stmt, "i", $course_id);
    mysqli_stmt_execute($course_stmt);
    $course_result = mysqli_stmt_get_result($course_stmt);
    
    if (mysqli_num_rows($course_result) == 0) {
        echo "Cours non trouvé.";
        exit;
    }

    $course = mysqli_fetch_assoc($course_result);

    // Récupérer les leçons associées à ce cours
    $lesson_query = "SELECT * FROM lessons WHERE course_id = ?";
    $lesson_stmt = mysqli_prepare($conn, $lesson_query);
    mysqli_stmt_bind_param($lesson_stmt, "i", $course_id);
    mysqli_stmt_execute($lesson_stmt);
    $lesson_result = mysqli_stmt_get_result($lesson_stmt);

    // Récupérer les exercices associés à ce cours
    $exercise_query = "SELECT * FROM exercises WHERE course_id = ?";
    $exercise_stmt = mysqli_prepare($conn, $exercise_query);
    mysqli_stmt_bind_param($exercise_stmt, "i", $course_id);
    mysqli_stmt_execute($exercise_stmt);
    $exercise_result = mysqli_stmt_get_result($exercise_stmt);
} else {
    echo "Aucun cours spécifié.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercices du Cours - <?php echo htmlspecialchars($course['title']); ?></title>
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Amatic+SC:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

</head>
<body>
<?php include('../includes/header_trainer.php'); ?>

<h2>Exercices du Cours: <?php echo htmlspecialchars($course['title']); ?></h2>

<h3>Leçons :</h3>
<?php if (mysqli_num_rows($lesson_result) > 0) : ?>
    <ul>
        <?php while ($lesson = mysqli_fetch_assoc($lesson_result)) : ?>
            <li><?php echo htmlspecialchars($lesson['title']); ?></li>
        <?php endwhile; ?>
    </ul>
<?php else : ?>
    <p>Aucune leçon trouvée pour ce cours.</p>
<?php endif; ?>

<h3>Exercices :</h3>
<?php if (mysqli_num_rows($exercise_result) > 0) : ?>
    <table border="1">
        <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
        <?php while ($exercise = mysqli_fetch_assoc($exercise_result)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($exercise['title']); ?></td>
                <td><?php echo htmlspecialchars($exercise['description']); ?></td>
                <td><?php echo htmlspecialchars($exercise['type']); ?></td>
                <td>
                    <?php if ($exercise['fichier']) : ?>
                        <a href="../uploads/<?php echo htmlspecialchars($exercise['fichier']); ?>" download>Télécharger</a>
                    <?php else : ?>
                        <p>Aucun fichier associé.</p>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else : ?>
    <p>Aucun exercice trouvé pour ce cours.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
</body>
</html>