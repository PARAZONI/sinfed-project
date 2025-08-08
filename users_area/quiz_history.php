<?php
include('../config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// récupérer la liste des quiz auxquels l'utilisateur a répondu
$query = "
    SELECT DISTINCT q.id AS quiz_id, q.title, q.description, MAX(ua.date_submitted) as last_attempt
    FROM quizzes q
    JOIN user_answers ua ON q.id = ua.quiz_id
    WHERE ua.user_id = ?
    GROUP BY q.id, q.title, q.description
    ORDER BY last_attempt DESC
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Mes Quiz Passés</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Dernière tentative</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($quiz = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                    <td><?php echo htmlspecialchars($quiz['description']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($quiz['last_attempt'])); ?></td>
                    <td>
                        <a href="quiz_result.php?quiz_id=<?php echo $quiz['quiz_id']; ?>" class="btn btn-sm btn-outline-primary">Voir le résultat</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Vous n'avez encore participé à aucun quiz.</div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Retour</a>
</div>
</body>
</html>