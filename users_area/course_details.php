<?php
// Inclure la connexion à la base de données
include('../config/db.php');

// Vérifier si une session est active avant de la démarrer
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'élève est connecté
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../login.php');
    exit;
}

$student_id = $_SESSION['user_id']; // Récupérer l'ID de l'élève depuis la session

// Vérifier si l'ID du cours est présent dans l'URL
if (!isset($_GET['course_id'])) {
    echo "Aucun cours sélectionné.";
    exit();
}

$course_id = intval($_GET['course_id']); // Récupérer l'ID du cours

// Vérifier si l'élève est inscrit à ce cours
$query_check = "SELECT * FROM student_courses WHERE student_id = ? AND course_id = ?";
$stmt_check = mysqli_prepare($conn, $query_check);
mysqli_stmt_bind_param($stmt_check, "ii", $student_id, $course_id);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result_check) === 0) {
    echo "Vous n'êtes pas inscrit à ce cours.";
    exit();
}

// Récupérer les détails du cours
$query_details = "SELECT * FROM courses WHERE course_id = ?";
$stmt_details = mysqli_prepare($conn, $query_details);
mysqli_stmt_bind_param($stmt_details, "i", $course_id);
mysqli_stmt_execute($stmt_details);
$result_details = mysqli_stmt_get_result($stmt_details);

if (mysqli_num_rows($result_details) === 0) {
    echo "Cours introuvable.";
    exit();
}

$course = mysqli_fetch_assoc($result_details); // Obtenir les détails du cours
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du cours</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


</head>
<body>
    <?php include '../includes/header.php'; ?>

    <a href="../users_area/voir_mes_cours.php" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> Retour
</a>


    <h1>Détails du cours : <?php echo htmlspecialchars($course['title']); ?></h1>

    <div class="course-details">
        <p><strong>Description :</strong> <?php echo nl2br(htmlspecialchars($course['description'] ?? 'Aucune description disponible')); ?></p>
        <p><strong>Date de début :</strong> <?php echo htmlspecialchars($course['start_date'] ?? 'Date non disponible'); ?></p>
        <p><strong>Statut :</strong> <?php echo htmlspecialchars($course['status'] ?? 'Statut non disponible'); ?></p>
    </div>

    <?php 
    // Vérifier si un fichier est disponible pour le cours
    $file_path = trim($course['file_path']); // Nettoyer le chemin du fichier

    if (!empty($file_path)) {
        $absolute_path = "../" . $file_path; // Construire le chemin réel du fichier

        if (file_exists($absolute_path)) {
            $file_url = "afficher_pdf.php?file=" . urlencode(basename($file_path));
        } else {
            echo "<p style='color:red;'>Le fichier n'existe pas sur le serveur : " . htmlspecialchars($file_path) . "</p>";
            $file_url = null;
        }
    } else {
        $file_url = null;
    }
    ?>

    <?php if ($file_url): ?>
        <div class="course-resource">
            <p><strong>Ressource :</strong> 
                <a href="<?php echo $file_url; ?>" target="_blank">Télécharger le fichier</a>
            </p>
            <iframe src="<?php echo $file_url; ?>" width="100%" height="500px"></iframe>
        </div>
    <?php else: ?>
        <p>Aucune ressource disponible pour ce cours.</p>
    <?php endif; ?>

    <a href="mes_cours.php">Retour à mes cours</a>

    <?php include '../includes/footer.php'; ?>
</body>
</html>