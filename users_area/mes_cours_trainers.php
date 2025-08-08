<?php
// Inclure la connexion à la base de données
include('../config/db.php');

// Vérifier si une session est active avant de la démarrer
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si le formateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'trainer') {
    header("Location: login.php");
    exit();
}

$trainer_id = $_SESSION['user_id']; // Récupérer l'ID du formateur depuis la session

// Récupérer la date actuelle
$current_date = date('Y-m-d');

// Mettre à jour le statut des cours en fonction des dates
$update_status_query = "
    UPDATE courses
    SET status = CASE 
        WHEN start_date > ? THEN 'À venir'
        WHEN end_date < ? THEN 'Terminé'
        ELSE 'En cours'
    END
    WHERE trainer_id = ?";

$stmt_update_status = mysqli_prepare($conn, $update_status_query);
mysqli_stmt_bind_param($stmt_update_status, "ssi", $current_date, $current_date, $trainer_id);
mysqli_stmt_execute($stmt_update_status);

// Récupérer les cours associés au formateur spécifique après la mise à jour
$query_courses = "
    SELECT * FROM courses
    WHERE trainer_id = ?";  // Assurez-vous que la colonne trainer_id est présente dans la table courses
$stmt_courses = mysqli_prepare($conn, $query_courses);
mysqli_stmt_bind_param($stmt_courses, "i", $trainer_id);
mysqli_stmt_execute($stmt_courses);
$result_courses = mysqli_stmt_get_result($stmt_courses);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Cours</title>
    <!-- Bootstrap 4 CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FontAwesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css"> <!-- Ton CSS personnalisé -->
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


</head>
<style>
    /* Styles personnalisés */
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
</style>

<body>

<?php include('../includes/header_trainer.php'); ?>


<div class="container mt-4">
    <a href="../users_area/trainer_home.php" class="btn btn-primary mb-4">
        <i class="fas fa-arrow-left"></i> Retour
    </a>

    <h2>Mes cours</h2>
    <?php
    if (mysqli_num_rows($result_courses) > 0) {
        while ($course = mysqli_fetch_assoc($result_courses)) {
            // Logique pour afficher le statut du cours en fonction des dates
            $course_status = $course['status']; // Récupérer le statut du cours depuis la base de données

            echo "<div class='card mb-3'>";
            echo "<div class='card-body'>";
            echo "<h4 class='card-title'>" . htmlspecialchars($course['title']) . "</h4>";
            echo "<p class='card-text'>" . htmlspecialchars($course['description']) . "</p>";
            echo "<p><strong>Date de début :</strong> " . htmlspecialchars($course['start_date']) . "</p>";
            echo "<p><strong>Date de fin :</strong> " . htmlspecialchars($course['end_date']) . "</p>";
            echo "<p><strong>Statut :</strong> " . htmlspecialchars($course_status) . "</p>";
            echo "<a href='course_lessons_trainers.php?course_id=" . $course['course_id'] . "' class='btn btn-info'>Accéder aux leçons</a>";
            echo "</div></div>";
        }
    } else {
        echo "<p>Vous n'avez créé aucun cours pour le moment.</p>";
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