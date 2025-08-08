<?php
include('../config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$formateur_id = $_SESSION['user_id'];

// Vérifie si l'ID du quiz est fourni
if (!isset($_GET['quiz_id'])) {
    header("Location: dashboard.php");
    exit;
}

$quiz_id = $_GET['quiz_id'];

// Vérifie que le quiz appartient bien au formateur connecté
$check_query = "SELECT * FROM quizzes WHERE quiz_id = ? AND formateur_id = ?";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $quiz_id, $formateur_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) === 0) {
    echo "Accès refusé.";
    exit;
}

// Récupération des résultats des élèves
$results_query = "
    SELECT DISTINCT ua.user_id, u.nom_complet, MAX(ua.date_submitted) as last_attempt,
           (SELECT COUNT(*) FROM user_answers WHERE user_id = ua.user_id AND quiz_id = ? AND is_correct = 1) as score,
           (SELECT COUNT(*) FROM questions WHERE quiz_id = ?) as total_questions
    FROM user_answers ua
    JOIN users u ON ua.user_id = u.user_id
    WHERE ua.quiz_id = ?
    GROUP BY ua.user_id, u.nom_complet
    ORDER BY last_attempt DESC
";
$stmt = mysqli_prepare($conn, $results_query);
mysqli_stmt_bind_param($stmt, "iii", $quiz_id, $quiz_id, $quiz_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats du Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Résultats des élèves</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Élève</th>
                    <th>Dernière tentative</th>
                    <th>Score</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nom_complet']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['last_attempt'])); ?></td>
                        <td><?php echo $row['score'] . ' / ' . $row['total_questions']; ?></td>
                        <td>
                            <a href="student_answers.php?quiz_id=<?php echo $quiz_id; ?>&user_id=<?php echo $row['user_id']; ?>" class="btn btn-outline-primary btn-sm">Voir réponses</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Aucune participation encore enregistrée pour ce quiz.</div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Retour</a>
</div>
</body>
</html>