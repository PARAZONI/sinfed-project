<?php
// Inclure la connexion à la base de données
include('../config/db.php');

// Vérifier si une session est active avant de la démarrer
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'élève est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les cours auxquels l'élève est inscrit
$query_my_courses = "
    SELECT c.course_id, c.title, c.description, c.start_date, c.status 
    FROM student_courses sc
    JOIN courses c ON sc.course_id = c.course_id
    WHERE sc.student_id = ?";
$stmt_my_courses = mysqli_prepare($conn, $query_my_courses);
mysqli_stmt_bind_param($stmt_my_courses, "i", $user_id);
mysqli_stmt_execute($stmt_my_courses);
$result_my_courses = mysqli_stmt_get_result($stmt_my_courses);

// Gestion de l'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $course_id = intval($_POST['course_id']); // Sécuriser la saisie

    // Vérifier si l'élève est déjà inscrit à ce cours
    $check_query = "SELECT * FROM student_courses WHERE student_id = ? AND course_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $course_id);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($check_result) > 0) {
        $message = "<div class='alert alert-warning' role='alert'>Vous êtes déjà inscrit à ce cours.</div>";
    } else {
        // Inscrire l'élève au cours
        $enroll_query = "INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $enroll_query);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $course_id);

        if (mysqli_stmt_execute($stmt)) {
            $message = "<div class='alert alert-success' role='alert'>Inscription réussie au cours.</div>";
            header("Location: enroll_course.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger' role='alert'>Erreur lors de l'inscription au cours.</div>";
        }
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription à un cours</title>
    <!-- Bootstrap 4 CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FontAwesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


</head>

<style>
    /* Styles personnalisés */

    /* Configuration du header */
    .navbar-custom {
        background-color: #FF5722; /* Orange */
        height: 105px;
    }
    .navbar-custom .nav-link {
        color: white !important;
    }
    .navbar-custom .nav-link:hover {
        color: #4CAF50 !important; /* Vert au survol */
    }
    .profile-pic {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }

    /* Image de fond qui s'étend sur toute la page */
    body {
        background-image: url('../assets1/image_voir_cours.jpeg'); /* Remplacez par votre image */
        background-size: cover;
        background-position: top center;
        background-attachment: fixed; /* Pour que l'image reste fixe en arrière-plan */
        min-height: 100vh; /* S'assurer que la page couvre toute la hauteur de l'écran */
    }

    

    /* Assurer que le contenu reste défilable au-dessus de l'image */
    .container {
        z-index: 1;
        position: relative;
        overflow: auto;
    }

</style>

<body>

<?php include('../includes/header_student.php'); ?>

<div class="container mt-4 main-content">
    <a href="../users_area/student_home.php" class="btn btn-primary mb-4">
        <i class="fas fa-arrow-left"></i> Retour
    </a>

    <h2>Mes cours</h2>

    <?php
    if (isset($message)) {
        echo $message;
    }

    if (mysqli_num_rows($result_my_courses) > 0) {
        while ($course = mysqli_fetch_assoc($result_my_courses)) {
            echo "<div class='card mb-3'>";
            echo "<div class='card-body'>";
            echo "<h4 class='card-title'>" . htmlspecialchars($course['title']) . "</h4>";
            echo "<p class='card-text'>" . htmlspecialchars($course['description']) . "</p>";
            echo "<p><strong>Date de début :</strong> " . htmlspecialchars($course['start_date']) . "</p>";
            echo "<p><strong>Statut :</strong> " . htmlspecialchars($course['status'] ?? 'Non défini') . "</p>";
            echo "<a href='course_lessons.php?course_id=" . $course['course_id'] . "' class='btn btn-info'>Accéder aux leçons</a>";
            echo "</div></div>";
        }
    } else {
        echo "<p>Vous n'êtes inscrit à aucun cours pour le moment.</p>";
    }
    ?>
</div>

<?php include '../includes/footer.php'; ?>

<!-- Bootstrap 4 CDN JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>