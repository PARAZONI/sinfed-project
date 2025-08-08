<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur (incluant l'email)
$query = "SELECT * FROM students WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

// Stocker l'email dans la session
$_SESSION['user_email'] = $user_data['email'];

$query_courses = "SELECT * FROM enrollments e JOIN courses c ON e.course_id = c.course_id WHERE e.student_id = ?";
$stmt_courses = mysqli_prepare($conn, $query_courses);
mysqli_stmt_bind_param($stmt_courses, "i", $user_id);
mysqli_stmt_execute($stmt_courses);
$courses_result = mysqli_stmt_get_result($stmt_courses);

mysqli_stmt_close($stmt_courses);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Élève</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        .card {
            margin-top: 20px;
        }
        .course-card {
            background-color: #f8f9fa;
        }
        .btn-primary {
            background-color: #4CAF50;
            border: none;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .footer {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <h1>Bienvenue sur votre tableau de bord, <?php echo $_SESSION['user_name']; ?> !</h1>
    <p>Accédez à vos cours, suivez votre progression et inscrivez-vous à de nouveaux cours.</p>
</div>

<!-- Main content -->
<div class="container mt-5">
    <div class="row">
        <!-- Colonne des informations de l'élève -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Mon Profil</h4>
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> <?php echo $_SESSION['user_name']; ?></p>
                    <p><strong>Email :</strong> <?php echo $_SESSION['user_email']; ?></p>
                    <p><strong>Rôle :</strong> Élève</p>
                    <a href="edit_profile.php" class="btn btn-primary btn-block">Modifier mon profil</a>
                </div>
            </div>
        </div>

        <!-- Colonne des cours inscrits -->
        <div class="col-md-8">
            <h3>Mes Cours</h3>
            <?php if (mysqli_num_rows($courses_result) > 0) : ?>
                <div class="row">
                    <?php while ($row = mysqli_fetch_assoc($courses_result)) : ?>
                        <div class="col-md-6">
                            <div class="card course-card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['title']; ?></h5>
                                    <p><strong>Description :</strong> <?php echo $row['description']; ?></p>
                                    <p><strong>Dates :</strong> <?php echo $row['start_date']; ?> - <?php echo $row['end_date']; ?></p>
                                    <a href="course_details.php?course_id=<?php echo $row['course_id']; ?>" class="btn btn-primary btn-block">Voir les détails</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p class="alert alert-warning">Vous n'êtes inscrit à aucun cours pour le moment. Inscrivez-vous à un cours dès maintenant !</p>
            <?php endif; ?>

            <!-- Inscription à un nouveau cours -->
            <div class="mt-4 mb-5">
                <h3>Inscrivez-vous à un nouveau cours</h3>
                <a href="available_courses.php" class="btn btn-primary">Voir les cours disponibles</a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2025 SINFED Academy. Tous droits réservés.</p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>