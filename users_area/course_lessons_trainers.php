<?php
ob_start(); // Démarrer la mise en mémoire tampon de sortie

include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si le formateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'trainer') {
    header("Location: login.php");
    exit();
}

// Récupération des paramètres GET
$course_id = $_GET['course_id'] ?? null;
$lesson_id = $_GET['lesson_id'] ?? null;

if (!$course_id) {
    echo "Aucun cours sélectionné.";
    exit;
}

// Récupération du titre du cours
$query_course = "SELECT title FROM courses WHERE course_id = ?";
$stmt_course = mysqli_prepare($conn, $query_course);
mysqli_stmt_bind_param($stmt_course, "i", $course_id);
mysqli_stmt_execute($stmt_course);
$result_course = mysqli_stmt_get_result($stmt_course);
$course = mysqli_fetch_assoc($result_course);

if (!$course) {
    echo "Cours introuvable.";
    exit;
}

// Sélectionner la première leçon si aucune n'est spécifiée
if (!$lesson_id) {
    $query_first_lesson = "SELECT lesson_id FROM lessons WHERE course_id = ? ORDER BY lesson_number ASC LIMIT 1";
    $stmt_first_lesson = mysqli_prepare($conn, $query_first_lesson);
    mysqli_stmt_bind_param($stmt_first_lesson, "i", $course_id);
    mysqli_stmt_execute($stmt_first_lesson);
    $result_first_lesson = mysqli_stmt_get_result($stmt_first_lesson);
    $first_lesson = mysqli_fetch_assoc($result_first_lesson);
    $lesson_id = $first_lesson['lesson_id'] ?? null;
}

// Récupérer la leçon actuelle
$query_lesson = "SELECT lesson_id, title, content, file_path FROM lessons WHERE course_id = ? AND lesson_id = ?";
$stmt_lesson = mysqli_prepare($conn, $query_lesson);
mysqli_stmt_bind_param($stmt_lesson, "ii", $course_id, $lesson_id);
mysqli_stmt_execute($stmt_lesson);
$result_lesson = mysqli_stmt_get_result($stmt_lesson);
$lesson = mysqli_fetch_assoc($result_lesson);

if (!$lesson) {
    echo " Cette Leçon n'est pas encore disponible pour le moment.";
    header("Location: ../users_area/mes_cours.php");

    exit;
}

// Récupération des leçons du cours
$query_lessons = "SELECT lesson_id, title, lesson_number FROM lessons WHERE course_id = ? ORDER BY lesson_number ASC";
$stmt_lessons = mysqli_prepare($conn, $query_lessons);
mysqli_stmt_bind_param($stmt_lessons, "i", $course_id);
mysqli_stmt_execute($stmt_lessons);
$result_lessons = mysqli_stmt_get_result($stmt_lessons);
$lessons = mysqli_fetch_all($result_lessons, MYSQLI_ASSOC);

// Mise à jour de la progression et redirection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finish'])) {
    // Redirection vers la page d'accueil des formateurs
    header("Location: ../users_area/trainer_home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leçon : <?= htmlspecialchars($lesson['title']) ?></title>
    <link rel="stylesheet" href="../assets/css_course_leçon.css">
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

    <div class="course-wrapper">
        <a href="../users_area/trainer_home.php" class="btn-back">Retour aux cours</a>

        <h1>Leçon : <?= htmlspecialchars($lesson['title']) ?></h1>

        <div class="lesson-card">
            <h3><?= htmlspecialchars($lesson['title']) ?></h3>
            <div><?= nl2br(htmlspecialchars($lesson['content'])) ?></div>

            <?php if (!empty($lesson['file_path']) && file_exists($lesson['file_path'])): ?>
                <div class="resource">
                    <p><strong>Ressource :</strong> <a href="<?= $lesson['file_path'] ?>" target="_blank">Télécharger</a></p>
                    <?php if (strtolower(pathinfo($lesson['file_path'], PATHINFO_EXTENSION)) == 'pdf'): ?>
                        <iframe src="<?= $lesson['file_path'] ?>" width="100%" height="500px"></iframe>
                    <?php elseif (in_array(strtolower(pathinfo($lesson['file_path'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <img src="<?= $lesson['file_path'] ?>" alt="Image de la leçon">
                    <?php else: ?>
                        <p>Fichier non affichable.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p>Aucun fichier disponible.</p>
            <?php endif; ?>

            <!-- Navigation entre les leçons -->
            <?php
            $next_lesson_id = null;
            foreach ($lessons as $index => $lesson_item) {
                if ($lesson_item['lesson_id'] == $lesson_id && isset($lessons[$index + 1])) {
                    $next_lesson_id = $lessons[$index + 1]['lesson_id'];
                    break;
                }
            }

            if ($next_lesson_id): ?>
                <a href="?course_id=<?= $course_id ?>&lesson_id=<?= $next_lesson_id ?>" class="btn-next">Leçon suivante</a>
            <?php else: ?>
                <!-- Ajout du bouton pour terminer le cours -->
                <form method="POST">
                    <button type="submit" name="finish" class="btn-finish">Terminer le cours</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php
ob_end_flush(); // Libère la mémoire tampon et envoie tout au navigateur
?>