<?php
include('../config/db.php');
session_start();

// Vérifier si l'utilisateur est connecté
$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null; // Récupérer le rôle de l'utilisateur

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les cours
$query_courses = "SELECT * FROM courses";
$result_courses = mysqli_query($conn, $query_courses);

$current_date = date('Y-m-d');

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? null;

    if ($course_id === null) {
        $error_message = "Veuillez sélectionner un cours.";
    } else {
        // Vérifier si déjà inscrit
        $check_query = "SELECT * FROM student_courses WHERE student_id = ? AND course_id = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $course_id);
        mysqli_stmt_execute($stmt);
        $check_result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($check_result) > 0) {
            $error_message = "Vous êtes déjà inscrit à ce cours.";
        } else {
            // Inscrire au cours
            $enroll_query = "INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)";
            $stmt_enroll = mysqli_prepare($conn, $enroll_query);
            mysqli_stmt_bind_param($stmt_enroll, "ii", $user_id, $course_id);

            if (mysqli_stmt_execute($stmt_enroll)) {
                $success_message = "Inscription réussie au cours.";

                // Récupérer infos du cours
                $course_query = "SELECT title, trainer_id FROM courses WHERE course_id = ?";
                $stmt_course = mysqli_prepare($conn, $course_query);
                mysqli_stmt_bind_param($stmt_course, "i", $course_id);
                mysqli_stmt_execute($stmt_course);
                $course_result = mysqli_stmt_get_result($stmt_course);
                $course = mysqli_fetch_assoc($course_result);

                $course_title = $course['title'];
                $trainer_id = $course['trainer_id'];

                // Notification élève
                $student_message = "Vous vous êtes inscrit au cours : $course_title.";
                $notif_student = "INSERT INTO notifications (user_id, role, message) VALUES (?, 'student', ?)";
                $stmt_notif_student = mysqli_prepare($conn, $notif_student);
                mysqli_stmt_bind_param($stmt_notif_student, "is", $user_id, $student_message);
                mysqli_stmt_execute($stmt_notif_student);
                mysqli_stmt_close($stmt_notif_student);

                // Notification formateur
                $trainer_message = "Un nouvel élève s'est inscrit à votre cours : $course_title.";
                $notif_trainer = "INSERT INTO notifications (user_id, role, message) VALUES (?, 'trainer', ?)";
                $stmt_notif_trainer = mysqli_prepare($conn, $notif_trainer);
                mysqli_stmt_bind_param($stmt_notif_trainer, "is", $trainer_id, $trainer_message);
                mysqli_stmt_execute($stmt_notif_trainer);
                mysqli_stmt_close($stmt_notif_trainer);

                mysqli_stmt_close($stmt_course);
                mysqli_stmt_close($stmt_enroll);
                
                // Redirection
                header("Location: ../users_area/mes_cours.php");
                exit();
            } else {
                $error_message = "Erreur lors de l'inscription.";
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription à un cours</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


</head>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    /* Image de fond */
    .background-container {
        position: relative;
        height: 100vh;
        background-image: url('../assets1/image_enroll_cours.jpeg'); /* Remplacez par le chemin de votre image */
        background-size: cover;
        background-position: center;
    }

    /* Hero Section */
    .hero-section {
        text-align: center;
        padding: 50px 20px;
        color: #fff;
        font-size: 2rem;
        font-weight: bold;
        background: rgba(0, 0, 0, 0.5);
    }

    /* Conteneur pour le bouton Retour */
    .btn-container {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 10; /* S'assurer que le bouton soit au-dessus de l'image de fond */
    }

    .btn-container .btn-custom {
        background-color: #87CEED;
        padding: 10px 20px;
        color: white;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-container .btn-custom:hover {
        background-color: #FF3D00;
    }

    .form-container {
        max-width: 600px;
        margin: 100px auto; /* Centrer le formulaire */
        padding: 20px;
        background: rgba(255, 255, 255, 0.8); /* Arrière-plan semi-transparent */
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 10; /* S'assurer que le formulaire soit au-dessus de l'image de fond */
    }

    .form-container select, .form-container button {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .form-container button {
        background-color: #87CEED;
        color: white;
        cursor: pointer;
    }

    .form-container button:hover {
        background-color: #FF3D00;
    }

    .success-message, .error-message {
        margin: 20px;
        padding: 15px;
        border-radius: 4px;
        color: white;
        font-weight: bold;
    }

    .success-message {
        background-color: #4CAF50;
    }

    .error-message {
        background-color: #f44336;
    }

    .course-item {
        margin: 10px 0;
        padding: 15px;
        border-radius: 5px;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
    }

    .course-item h3 {
        font-size: 1.5rem;
    }

    .course-item p {
        font-size: 1rem;
    }
</style>

<body>

<?php include('../includes/header_student.php'); ?>

<div class="background-container">
    <div class="hero-section">
        <h1>Inscription à un cours</h1>
    </div>

    <!-- Bouton Retour positionné correctement au-dessus de l'image -->
    <div class="btn-container">
        <a href="../users_area/student_home.php" class="btn-custom">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="form-container">
        <?php if ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php elseif ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Formulaire d'inscription -->
        <form method="POST" action="enroll_course.php">
            <label for="course_id">Sélectionnez un cours :</label>
            <select name="course_id" id="course_id" required>
                <option value="">-- Sélectionnez --</option>
                <?php
                if (mysqli_num_rows($result_courses) > 0) {
                    while ($course = mysqli_fetch_assoc($result_courses)) {
                        $start_date = $course['start_date'];
                        $end_date = $course['end_date'];

                        if ($current_date < $start_date) {
                            $status = 'À venir';
                        } elseif ($current_date >= $start_date && $current_date <= $end_date) {
                            $status = 'En cours';
                        } else {
                            $status = 'Terminé';
                        }

                        if ($status !== 'Terminé') {
                            echo "<option value='" . $course['course_id'] . "'>" . htmlspecialchars($course['title']) . " - $status</option>";
                        }
                    }
                } else {
                    echo "<option value=''>Aucun cours disponible</option>";
                }
                ?>
            </select>
            <button type="submit" name="enroll">
                <i class="fas fa-check"></i> S'inscrire
            </button>
        </form>
    </div>
</div>

<script>
    // Fonction pour afficher une notification et jouer un son
    function showNotificationAndSound() {
        alert("Inscription réussie au cours !");
        var audio = new Audio('Sweet_Alert_Sound_2_SOUND_Effect(128k).mp3');
        audio.play();
    }

    <?php if ($success_message): ?>
        window.onload = function() {
            showNotificationAndSound();
        };
    <?php endif; ?>
</script>

<?php include('../includes/footer.php'); ?>
</body>
</html>