<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'trainer') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');

// Récupérer tous les exercices soumis
$query = "SELECT se.id, e.title, u.first_name, u.last_name, se.correction, se.note, se.file_path, se.student_id 
          FROM student_exercises se
          JOIN exercises e ON se.exercise_id = e.exercise_id
          JOIN users u ON se.student_id = u.id  -- Changement de 'u.user_id' à 'u.id'
          WHERE se.status = 'Soumis'";$result = mysqli_query($conn, $query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier que les données nécessaires sont envoyées via POST
    if (isset($_POST['exercise_id']) && isset($_POST['student_id'])) {
        $exercise_id = $_POST['exercise_id'];
        $student_id = $_POST['student_id'];
        $correction = mysqli_real_escape_string($conn, $_POST['correction']);
        $note = $_POST['note'];

        // Mise à jour de la correction et de la note
        $update = "UPDATE student_exercises SET correction = ?, note = ?, status = 'Corrigé' WHERE exercise_id = ? AND student_id = ?";
        $stmt = mysqli_prepare($conn, $update);
        mysqli_stmt_bind_param($stmt, "ssii", $correction, $note, $exercise_id, $student_id);
        mysqli_stmt_execute($stmt);

        echo "<div class='alert alert-success mt-3'>Correction soumise avec succès !</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Erreur : Données manquantes.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Corriger les exercices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-3">Gestion des exercices soumis</h3>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h5><?php echo htmlspecialchars($row['title']); ?> - Soumis par : <?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></h5>
        </div>
        <div class="card-body">
            <p><strong>Correction actuelle :</strong> <?php echo htmlspecialchars($row['correction'] ?? 'Aucune correction'); ?></p>
            <p><strong>Note actuelle :</strong> <?php echo htmlspecialchars($row['note'] ?? 'Non notée'); ?></p>

            <?php if (!empty($row['file_path'])): ?>
                <p><strong>Fichier soumis :</strong> <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">Télécharger</a></p>
            <?php endif; ?>

            <form method="POST" action="correct_exercises.php">
                <input type="hidden" name="exercise_id" value="<?php echo $row['id']; ?>"> <!-- Utiliser 'id' au lieu de 'exercise_id' -->
                <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">

                <div class="mb-3">
                    <label for="correction" class="form-label">Correction :</label>
                    <textarea name="correction" id="correction" class="form-control" rows="4" required><?php echo htmlspecialchars($row['correction'] ?? ''); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Note :</label>
                    <input type="number" name="note" id="note" class="form-control" value="<?php echo htmlspecialchars($row['note'] ?? ''); ?>" required min="0" max="20">
                </div>

                <button type="submit" class="btn btn-primary">Soumettre la correction</button>
            </form>
        </div>
    </div>
    <?php endwhile; ?>
</div>
</body>
</html>