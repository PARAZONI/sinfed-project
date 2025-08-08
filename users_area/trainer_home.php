<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'trainer') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');

$user_id = $_SESSION['user_id'];

// récupérer les informations du formateur
$query = "SELECT * FROM trainers WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

// stocker l'email dans la session
$_SESSION['user_email'] = $user_data['email'];

// récupérer les cours créés par le formateur
$query_courses = "SELECT * FROM courses WHERE trainer_id = ?";
$stmt_courses = mysqli_prepare($conn, $query_courses);
mysqli_stmt_bind_param($stmt_courses, "i", $user_id);
mysqli_stmt_execute($stmt_courses);
$courses_result = mysqli_stmt_get_result($stmt_courses);

// récupérer les notifications non lues du formateur
$query_notifications = "SELECT * FROM notifications WHERE user_id = ? AND is_read = 'unread' ORDER BY created_at DESC";
$stmt_notifications = mysqli_prepare($conn, $query_notifications);
mysqli_stmt_bind_param($stmt_notifications, "i", $user_id);
mysqli_stmt_execute($stmt_notifications);
$notifications_result = mysqli_stmt_get_result($stmt_notifications);

mysqli_stmt_close($stmt_notifications);
mysqli_stmt_close($stmt_courses);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Formateur</title>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
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

    <style>
        body {
            background-color: black;
            color: #e4f2fe;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .dashboard-card {
            background: #008000;
            border: none;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: white;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }
        .dashboard-card i {
            font-size: 50px;
            margin-bottom: 10px;
            color: #ff8c00;
        }
        .dashboard-card h5 {
            font-size: 18px;
            font-weight: bold;
        }
        .btn-sinfed {
            background-color: #ff8c00;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            padding: 10px 15px;
            display: inline-block;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .btn-sinfed:hover {
            background-color: #e67e00;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<?php include('../includes/header_trainers.php'); ?>


<div class="container text-center">
    <h1 class="mb-4 text-success">👋 bienvenue, <strong><?php echo $_SESSION['user_name']; ?></strong> !</h1>
    
    <div class="row g-4">
        <!-- Notifications -->
        <div class="col-md-4">
            <div class="dashboard-card p-4">
                <i class="fas fa-bell"></i>
                <h5>Notifications</h5>
                <p>Consultez vos notifications récentes.</p>
                <ul class="list-unstyled">



            </ul>
                <a href="../users_area/notifications.php" class="btn-sinfed mt-2">Voir toutes les notifications</a>
            </div>
        </div>

        <!-- gestion du profil -->
        <div class="col-md-4">
            <div class="dashboard-card p-4">
                <i class="fas fa-user"></i>
                <h5>gestion du profil</h5>
                <p>modifiez vos informations personnelles.</p>
                <a href="../users_area/profile_trainers.php" class="btn-sinfed">voir et modifier</a>
            </div>
        </div>

        <!-- gestion des cours -->
        <div class="col-md-4">
            <div class="dashboard-card p-4">
                <i class="fas fa-chalkboard-teacher"></i>
                <h5>gestion des cours</h5>
                <p>ajoutez, modifiez et consultez vos cours.</p>
                <a href="../users_area/add_course.php" class="btn-sinfed">ajouter un cours</a>
                <a href="../users_area/mes_cours_trainers.php" class="btn-sinfed mt-2">voir mes cours</a>
            </div>
        </div>

        <!-- forum de discussion -->
        <div class="col-md-4">
            <div class="dashboard-card p-4">
                <i class="fas fa-comments"></i>
                <h5>forum de discussion</h5>
                <p>participez aux discussions avec les élèves.</p>
                <a href="../users_area/forum_list.php" class="btn-sinfed">accéder au forum</a>
            </div>
        </div>

        <!-- messages des élèves -->
        <div class="col-md-4">
            <div class="dashboard-card p-4">
                <i class="fas fa-envelope"></i>
                <h5>messages des élèves</h5>
                <p>consultez et répondez aux messages reçus.</p>
                <a href="../users_area/messages_formateur.php" class="btn-sinfed">Message formateur</a>
            </div>
        </div>

        <!-- Exercices -->
<div class="col-md-4">
    <div class="dashboard-card p-4">
        <i class="fas fa-edit"></i>
        <h5>Exercices</h5>
        <p> exercices & corrigés. </p>
        <div class="d-flex justify-content-between">
            <a href="../users_area/view_exercises_trainer.php" class="btn-sinfed me-1">Voir les l'exercices corrigés</a>
            <a href="../users_area/exercises_list.php" class="btn-sinfed">Corriger l'exercice</a>
        </div>
    </div>
</div>

        <!-- Ajouter une leçon -->
        <div class="col-md-4">
            <div class="dashboard-card p-4">
                <i class="fas fa-edit"></i>
                <h5>Ajouter une leçon</h5>
                <p>Ajouter une leçon facilement.</p>
                <a href="../users_area/add_lesson.php" class="btn-sinfed">Ajouter une leçon</a>
                <a href="../users_area/voir_exercise.php" class="btn-sinfed">Voir l'exercice</a>
            </div>
        </div>

        <!-- Ajouter un exercice -->
        <div class="col-md-4 mb-3">
            <div class="dashboard-card p-4">
                <i class="fas fa-edit"></i>
                <h5>Ajouter un exercice</h5>
                <p>Ajouter un exercice facilement.</p>
                <a href="../users_area/add_exercise.php" class="btn-sinfed">Ajouter un exercice</a>
                <a href="../users_area/add_quiz.php" class="btn-sinfed">Ajouter un quiz</a>

            </div>
        </div>

    </div>
</div>

<!-- bootstrap js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include('../includes/footer.php'); ?>

</body>
</html>