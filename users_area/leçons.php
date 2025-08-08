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

// Récupérer le cours
$course_id = $_GET['course_id'] ?? null;
if (!$course_id) {
    echo "Aucun cours sélectionné.";
    exit;
}

// Récupérer les leçons du cours
$query_lessons = "SELECT * FROM lessons WHERE course_id = ?";
$stmt = mysqli_prepare($conn, $query_lessons);
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$result_lessons = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leçons du Cours</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<h1>Leçons du Cours</h1>

<?php
if (mysqli_num_rows($result_lessons) > 0) {
    while ($lesson = mysqli_fetch_assoc($result_lessons)) {
        echo "<div class='lesson'>";
        echo "<h2>" . htmlspecialchars($lesson['title']) . "</h2>";
        echo "<p>" . nl2br(htmlspecialchars($lesson['content'])) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>Aucune leçon disponible pour ce cours.</p>";
}
?>

<?php include '../includes/footer.php'; ?>
</body>
</html><?php
// Inclure la connexion à la base de données
include('../config/db.php');
session_start();

// Vérifier si l'étudiant est connecté
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../login.php');
    exit;
}

// Récupérer le cours
$course_id = $_GET['course_id'] ?? null;
if (!$course_id) {
    echo "Aucun cours sélectionné.";
    exit;
}

// Récupérer les leçons du cours
$query_lessons = "SELECT * FROM lessons WHERE course_id = ?";
$stmt = mysqli_prepare($conn, $query_lessons);
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$result_lessons = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leçons du Cours</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<h1>Leçons du Cours</h1>

<?php
if (mysqli_num_rows($result_lessons) > 0) {
    while ($lesson = mysqli_fetch_assoc($result_lessons)) {
        echo "<div class='lesson'>";
        echo "<h2>" . htmlspecialchars($lesson['title']) . "</h2>";
        echo "<p>" . nl2br(htmlspecialchars($lesson['content'])) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>Aucune leçon disponible pour ce cours.</p>";
}
?>

<?php include '../includes/footer.php'; ?>
</body>
</html>