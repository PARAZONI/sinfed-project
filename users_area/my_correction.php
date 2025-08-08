<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');

// Récupérer les informations de correction et de note pour l'élève
$student_id = $_SESSION['user_id'];  // ID de l'élève connecté
$query = "SELECT e.title, se.correction, se.note 
          FROM student_exercises se
          JOIN exercises e ON se.exercise_id = e.exercise_id
          WHERE se.student_id = ? AND se.status = 'Corrigé'";  // Seulement les exercices corrigés
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fonction pour obtenir l'emoji en fonction de la note
function getEmojiForGrade($note) {
    if ($note >= 15) {
        return "😊"; // Heureux
    } elseif ($note >= 10) {
        return "😐"; // Neutre
    } else {
        return "😞"; // Triste
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma correction et ma note</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-3">Mes corrections et mes notes</h3>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5><?php echo htmlspecialchars($row['title']); ?> - Correction</h5>
                </div>
                <div class="card-body">
                    <p><strong>Correction :</strong> <?php echo nl2br(htmlspecialchars($row['correction'])); ?></p>
                    <p><strong>Note :</strong> <?php echo htmlspecialchars($row['note']); ?> <?php echo getEmojiForGrade($row['note']); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Aucune correction n'est encore disponible pour vous.</p>
    <?php endif; ?>
</div>
</body>
</html>