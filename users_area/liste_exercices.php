<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');
$user_id = $_SESSION['user_id'];

$query = "SELECT e.*, se.status, se.grade, se.correction 
          FROM exercises e
          JOIN student_courses sc ON e.course_id = sc.course_id
          LEFT JOIN student_exercises se 
          ON e.exercise_id = se.exercise_id AND se.student_id = ?
          WHERE sc.student_id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Exercices disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Tous les exercices disponibles</h2>
    <table class="table table-bordered bg-white shadow">
        <thead class="table-dark">
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Fichier</th>
                <th>Statut</th>
                <th>Note</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <?php if (!empty($row['file_path'])): ?>
                            <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">Télécharger</a>
                        <?php else: ?>
                            Aucun
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['status'] ?? 'Non traité'; ?></td>
                    <td>
                        <?php echo isset($row['grade']) ? $row['grade'] . "/20" : "-"; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] === 'Corrigé'): ?>
                            <!-- Afficher le bouton pour voir la correction si l'exercice est corrigé -->
                            <a href="view_correction.php?exercise_id=<?php echo $row['exercise_id']; ?>" class="btn btn-success btn-sm">Voir la correction</a>
                        <?php else: ?>
                            <!-- Afficher le bouton pour traiter l'exercice si ce n'est pas corrigé -->
                            <a href="treat_exercise.php?exercise_id=<?php echo $row['exercise_id']; ?>" class="btn btn-primary btn-sm">Traiter</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="../users_area/student_home.php" class="btn btn-secondary mt-3">Retour</a>
</div>
</body>
</html>