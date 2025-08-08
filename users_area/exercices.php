<?php
// Inclure la connexion à la base de données
include('../config/db.php');
session_start();

// Vérifier si l'étudiant est connecté
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../login.php');
    exit;
}

// Récupérer la leçon
$lesson_id = $_GET['lesson_id'] ?? null;
if (!$lesson_id) {
    echo "Aucune leçon sélectionnée.";
    exit;
}

// Récupérer les exercices de la leçon
$query_exercises = "SELECT * FROM exercises WHERE lesson_id = ?";
$stmt = mysqli_prepare($conn, $query_exercises);
mysqli_stmt_bind_param($stmt, "i", $lesson_id);
mysqli_stmt_execute($stmt);
$result_exercises = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercices de la Leçon</title>
    <link rel="stylesheet" href="../css/style.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


</head>
<body>
<?php include '../includes/header.php'; ?>

<h1>Exercices de la Leçon</h1>

<?php
if (mysqli_num_rows($result_exercises) > 0) {
    while ($exercise = mysqli_fetch_assoc($result_exercises)) {
        echo "<div class='exercise'>";
        echo "<h2>" . htmlspecialchars($exercise['title']) . "</h2>";
        echo "<p>" . nl2br(htmlspecialchars($exercise['description'])) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>Aucun exercice disponible pour cette leçon.</p>";
}
?>

<?php include '../includes/footer.php'; ?>
</body>
</html>