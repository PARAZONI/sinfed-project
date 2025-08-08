<?php
session_start();

// Vérification si l'élève est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');

// Récupérer l'ID de l'élève et l'ID de l'exercice à afficher
$user_id = $_SESSION['user_id'];
$exercise_id = isset($_GET['exercise_id']) ? (int)$_GET['exercise_id'] : 0;

if ($exercise_id == 0) {
    echo "Exercice invalide.";
    exit();
}

// Récupérer les détails de l'exercice et de la correction pour l'élève
$query_exercise_details = "
    SELECT e.title, e.description, e.file_path, se.grade, se.correction
    FROM exercises e
    LEFT JOIN student_exercises se ON e.exercise_id = se.exercise_id
    WHERE e.exercise_id = ? AND se.student_id = ?
";
$stmt_exercise_details = mysqli_prepare($conn, $query_exercise_details);
mysqli_stmt_bind_param($stmt_exercise_details, "ii", $exercise_id, $user_id);
mysqli_stmt_execute($stmt_exercise_details);
$result = mysqli_stmt_get_result($stmt_exercise_details);

// Vérifier si l'exercice existe et si l'élève a une correction pour cet exercice
if (mysqli_num_rows($result) == 0) {
    echo "Aucune correction disponible pour cet exercice.";
    exit();
}

$exercise = mysqli_fetch_assoc($result);

// Déterminer l'emoji en fonction de la note
$grade = (int)$exercise['grade'];
$emoji = ""; // par défaut aucun emoji

if ($grade >= 0 && $grade <= 5) {
    $emoji = "<img src='../assets1/img/emoji/AREmoji_20231112_221252_75.gif' alt='triste' style='height:40px;'>";
} elseif ($grade >= 6 && $grade <= 9) {
    $emoji = "<img src='../assets1/img/emoji/AREmoji_20231112_221253_410.gif' alt='moyen' style='height:40px;'>";
} elseif ($grade >= 10 && $grade <= 12) {
    $emoji = "<img src='../assets1/img/emoji/AREmoji_20231112_221252_143.gif' alt='content' style='height:40px;'>";
} elseif ($grade >= 13 && $grade <= 16) {
    $emoji = "<img src='../assets1/img/emoji/AREmoji_20231112_221253_472.gif' alt='excellent' style='height:40px;'>";
} elseif ($grade >= 17 && $grade <= 20) {
    $emoji = "<img src='../assets1/img/emoji/AREmoji_20231112_221255_729.gif' alt='excellent' style='height:40px;'>";
}// Fermer la connexion à la base de données
mysqli_stmt_close($stmt_exercise_details);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correction de l'exercice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
            background-image: url('../assets1/image_correction.jpeg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-attachment: fixed;

            
        }
        .container {
            max-width: 800px;
        }
        .card {
            border-radius: 10px;
        }
        .emoji {
            font-size: 2rem;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Correction de l'exercice</h1>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><?php echo htmlspecialchars($exercise['title']); ?></h3>
        </div>
        <div class="card-body">
            <p><strong>Description :</strong> <?php echo nl2br(htmlspecialchars($exercise['description'])); ?></p>

            <?php if (!empty($exercise['file_path'])): ?>
                <p><strong>Fichier de l'exercice :</strong> 
                    <a href="<?php echo htmlspecialchars($exercise['file_path']); ?>" target="_blank" class="btn btn-link">Télécharger</a>
                </p>
            <?php endif; ?>

            <p><strong>Note obtenue :</strong> <span class="emoji"><?php echo $emoji; ?></span> <?php echo htmlspecialchars($exercise['grade']); ?>/20</p>
            
            <div class="border rounded p-3 bg-light">
                <h5>Correction :</h5>
                <pre class="p-2 bg-white border"><?php echo nl2br(htmlspecialchars($exercise['correction'])); ?></pre>
            </div>

            <a href="../users_area/student_home.php" class="btn btn-secondary mt-3">Retour au tableau de bord</a>
        </div>
    </div>
</div>

</body>
</html>