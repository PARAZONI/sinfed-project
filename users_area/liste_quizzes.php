<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification de connexion
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupération des cours auxquels l'élève est inscrit
$query = "SELECT c.course_id, c.title, q.id AS quiz_id, q.title AS quiz_title, q.description
          FROM student_courses sc
          JOIN courses c ON sc.course_id = c.course_id
          JOIN quizzes q ON q.course_id = c.course_id
          WHERE sc.student_id = ?";
          $stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Quizzes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Quizzes Disponibles</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Cours</th>
                <th>Titre du Quiz</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['quiz_title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>
                            <a href="take_quiz.php?quiz_id=<?php echo $row['quiz_id']; ?>" class="btn btn-primary btn-sm">Commencer le Quiz</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">Aucun quiz disponible pour vos cours.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>