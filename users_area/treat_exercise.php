<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
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

// Traitement du formulaire lors de la soumission des réponses
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['user_id'];
    $response = $_POST['response']; // Supposons que l'exercice a une seule réponse texte

    // Insérer ou mettre à jour les réponses de l'élève
    $query_check = "SELECT * FROM student_exercises WHERE student_id = ? AND exercise_id = ?";
    $stmt_check = mysqli_prepare($conn, $query_check);
    mysqli_stmt_bind_param($stmt_check, "ii", $student_id, $exercise_id);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Mise à jour des réponses si elles existent déjà
        $query_update = "UPDATE student_exercises SET response = ?, status = 'pending' WHERE student_id = ? AND exercise_id = ?";
        $stmt_update = mysqli_prepare($conn, $query_update);
        mysqli_stmt_bind_param($stmt_update, "sii", $response, $student_id, $exercise_id);
        mysqli_stmt_execute($stmt_update);
    } else {
        // Insertion de la nouvelle réponse
        $query_insert = "INSERT INTO student_exercises (student_id, exercise_id, response, status) VALUES (?, ?, ?, 'pending')";
        $stmt_insert = mysqli_prepare($conn, $query_insert);
        mysqli_stmt_bind_param($stmt_insert, "iis", $student_id, $exercise_id, $response);
        mysqli_stmt_execute($stmt_insert);
    }

    // Rediriger vers la page des exercices avec un message de confirmation
    header("Location: view_exercises_élève.php?status=success");
    exit();
}

mysqli_stmt_close($stmt_exercise);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traiter l'exercice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
    <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">
    <style>
        body {
            background-image: url('../assets1/image_treat_exercices.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        form {
            margin-top: 220px;
        }
        h1 {
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center mb-4">Traiter l'exercice : <?php echo htmlspecialchars($exercise['title']); ?></h1>

    <form action="treat_exercise.php?exercise_id=<?php echo $exercise_id; ?>" method="POST">
        <div class="mb-3">
            <label for="response" class="form-label">Réponse :</label>
            <textarea id="response" name="response" class="form-control" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Soumettre la réponse</button>
    </form>
</div>
</body>
</html>