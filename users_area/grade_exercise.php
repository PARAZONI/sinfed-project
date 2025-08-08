<?php
session_start();

// Vérification du rôle de l'utilisateur
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'trainer') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');

// Vérification de l'ID de l'exercice
if (!isset($_GET['exercise_id']) || !is_numeric($_GET['exercise_id'])) {
    echo "Exercice non trouvé.";
    exit();
}

$exercise_id = $_GET['exercise_id'];

// Récupérer les détails de l'exercice
$query_exercise = "SELECT * FROM exercises WHERE exercise_id = ?";
$stmt_exercise = mysqli_prepare($conn, $query_exercise);
mysqli_stmt_bind_param($stmt_exercise, "i", $exercise_id);
mysqli_stmt_execute($stmt_exercise);
$exercise_result = mysqli_stmt_get_result($stmt_exercise);
$exercise = mysqli_fetch_assoc($exercise_result);

if (!$exercise) {
    echo "Exercice non trouvé.";
    exit();
}

// Récupérer les réponses des élèves pour cet exercice, y compris celles en statut "pending"
// Modification de la requête pour inclure les informations sur les élèves
$query_responses = "
    SELECT se.*, s.first_name, s.last_name
    FROM student_exercises se
    JOIN students s ON se.student_id = s.id
    WHERE se.exercise_id = ?";
$stmt_responses = mysqli_prepare($conn, $query_responses);
mysqli_stmt_bind_param($stmt_responses, "i", $exercise_id);
mysqli_stmt_execute($stmt_responses);
$responses_result = mysqli_stmt_get_result($stmt_responses);

// Traitement de la correction lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $correction = $_POST['correction'];
    $grade = $_POST['grade'];
    $status = 'corrected';

    // Mise à jour de la correction dans la base de données
    $query_update_correction = "UPDATE student_exercises SET correction = ?, grade = ?, status = ? WHERE student_id = ? AND exercise_id = ?";
    $stmt_update_correction = mysqli_prepare($conn, $query_update_correction);
    mysqli_stmt_bind_param($stmt_update_correction, "sisis", $correction, $grade, $status, $student_id, $exercise_id);
    mysqli_stmt_execute($stmt_update_correction);

    // Rediriger vers la page de correction
    header("Location: grade_exercise.php?exercise_id=" . $exercise_id);
    exit();
}

mysqli_stmt_close($stmt_exercise);
mysqli_stmt_close($stmt_responses);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corriger l'exercice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Ajout de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            <!-- Favicons -->
            <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

</head>
<body>
<div class="container mt-4">
    <!-- Titre avec icône -->
    <h1 class="text-center mb-4">
        <i class="fas fa-pencil-alt"></i> Corriger l'exercice : <?php echo htmlspecialchars($exercise['title']); ?>
    </h1>

    <?php if (mysqli_num_rows($responses_result) > 0) : ?>
        <?php while ($response = mysqli_fetch_assoc($responses_result)) : ?>
            <div class="mb-3">
                <h4>Réponse de l'élève : <?php echo htmlspecialchars($response['first_name']) . ' ' . htmlspecialchars($response['last_name']); ?></h4>
                <p><strong>Réponse :</strong> <?php echo nl2br(htmlspecialchars($response['response'] ?? '')); ?></p>

                <!-- Formulaire pour la correction -->
                <form action="grade_exercise.php?exercise_id=<?php echo $exercise_id; ?>" method="POST">
                    <input type="hidden" name="student_id" value="<?php echo $response['student_id']; ?>">

                    <div class="mb-3">
                        <label for="correction" class="form-label">Correction :</label>
                        <textarea id="correction" name="correction" class="form-control" rows="5" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="grade" class="form-label">Note :</label>
                        <input type="number" id="grade" name="grade" class="form-control" min="0" max="20" required>
                    </div>

                    <button type="submit" class="btn btn-success">Soumettre la correction</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <p>Aucun élève n'a traité cet exercice.</p>
    <?php endif; ?>

    <!-- Bouton retour avec icône -->
    <div class="mt-4">
        <button onclick="window.history.back();" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
    </div>

</div>
</body>
</html>