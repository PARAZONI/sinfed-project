<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'trainer') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');

$user_id = $_SESSION['user_id'];

// récupérer les cours créés par le formateur
$query_courses = "SELECT * FROM courses WHERE trainer_id = ?";
$stmt_courses = mysqli_prepare($conn, $query_courses);
mysqli_stmt_bind_param($stmt_courses, "i", $user_id);
mysqli_stmt_execute($stmt_courses);
$courses_result = mysqli_stmt_get_result($stmt_courses);

mysqli_stmt_close($stmt_courses);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercices par Cours</title>
    
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
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .header-title {
            font-size: 24px;
            font-weight: bold;
            color: #008000;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 30px;
        }
        .dashboard-card {
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: #333;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }
        .dashboard-card i {
            font-size: 50px;
            margin-bottom: 10px;
            color: #008000;
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
        .back-btn {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="header-title">📚 exercices par cours</h1>
    
    <div class="row g-4">
        <?php while ($course = mysqli_fetch_assoc($courses_result)) { ?>
            <div class="col-md-4">
                <div class="dashboard-card p-4">
                    <i class="fas fa-book"></i>
                    <h5><?php echo isset($course['title']) ? htmlspecialchars($course['title']) : 'nom du cours introuvable'; ?></h5>
                    <p>ajoutez ou modifiez les exercices pour ce cours.</p>
                    <a href="view_exercises.php?course_id=<?php echo $course['course_id']; ?>" class="btn-sinfed">voir les exercices</a>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="back-btn p-5">
        <a href="../users_area/trainer_home.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> retour au tableau de bord</a>
    </div>
</div>

<!-- bootstrap js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include '../includes/footer.php'; ?>

</body>
</html>