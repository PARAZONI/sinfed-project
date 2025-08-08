<?php
session_start();

// Vérifie si l'utilisateur est connecté et est un étudiant
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');

// Récupère l'ID de l'exercice depuis l'URL
$exercise_id = $_GET['exercise_id'];
$student_id = $_SESSION['user_id'];

// Récupère les détails de l'exercice
$query = "SELECT * FROM exercises WHERE exercise_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $exercise_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$exercise = mysqli_fetch_assoc($result);

// Vérifie si l'exercice existe
if (!$exercise) {
    echo "<div class='alert alert-danger mt-3'>Exercice non trouvé.</div>";
    exit();
}

// Traitement du formulaire lorsque l'utilisateur soumet une réponse
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = mysqli_real_escape_string($conn, $_POST['response']);
    $file_path = null;

    // Gestion de l'upload du fichier (optionnel)
    if (!empty($_FILES['file']['name'])) {
        $file_name = basename($_FILES['file']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . time() . "_" . $file_name;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_path = $target_file;
        }
    }

    // Vérifie si une soumission existe déjà pour cet exercice
    $check = "SELECT id FROM student_exercises WHERE student_id = ? AND exercise_id = ?";
    $stmt_check = mysqli_prepare($conn, $check);
    mysqli_stmt_bind_param($stmt_check, "ii", $student_id, $exercise_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        // Mise à jour si une soumission existe déjà
        $update = "UPDATE student_exercises SET correction = ?, file_path = ?, status = 'Soumis', submitted_at = NOW() WHERE student_id = ? AND exercise_id = ?";
        $stmt_update = mysqli_prepare($conn, $update);
        mysqli_stmt_bind_param($stmt_update, "ssii", $response, $file_path, $student_id, $exercise_id);
        mysqli_stmt_execute($stmt_update);
    } else {
        // Insertion si aucune soumission n'existe
        $insert = "INSERT INTO student_exercises (student_id, exercise_id, correction, file_path, status, submitted_at) VALUES (?, ?, ?, ?, 'Soumis', NOW())";
        $stmt_insert = mysqli_prepare($conn, $insert);
        mysqli_stmt_bind_param($stmt_insert, "iiss", $student_id, $exercise_id, $response, $file_path);
        mysqli_stmt_execute($stmt_insert);
    }

    echo "<div class='alert alert-success mt-3'>Exercice soumis avec succès !</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Traiter l'exercice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-3">Exercice : <?php echo htmlspecialchars($exercise['title']); ?></h3>
    <p><strong>Description :</strong> <?php echo htmlspecialchars($exercise['description']); ?></p>

    <?php if (!empty($exercise['file_path'])): ?>
        <p><strong>Fichier :</strong> <a href="<?php echo htmlspecialchars($exercise['file_path']); ?>" target="_blank">Télécharger</a></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="response" class="form-label">Votre réponse :</label>
            <textarea name="response" id="response" class="form-control" rows="6" required></textarea>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Joindre un fichier (optionnel) :</label>
            <input type="file" name="file" id="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>
</div>
</body>
</html>