<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$formateur_id = $_SESSION['user_id'];

// Récupération des cours pour le formateur
$courses_query = "SELECT course_id, title FROM courses WHERE trainer_id = ?";
$courses_stmt = mysqli_prepare($conn, $courses_query);
mysqli_stmt_bind_param($courses_stmt, "s", $formateur_id);
mysqli_stmt_execute($courses_stmt);
$courses_result = mysqli_stmt_get_result($courses_stmt);

// Traitement du formulaire pour ajouter un quiz
if (isset($_POST['ajouter_quiz'])) {
    $quiz_title = $_POST['quiz_title'];
    $quiz_description = $_POST['quiz_description'];
    $course_id = $_POST['course_id'];

    $query = "INSERT INTO quizzes (title, description, formateur_id, course_id) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $quiz_title, $quiz_description, $formateur_id, $course_id);

    if (mysqli_stmt_execute($stmt)) {
        $quiz_id = mysqli_insert_id($conn); // ID du quiz inséré
        header("Location: add_questions.php?quiz_id=$quiz_id");
        exit;
    } else {
        $message = "Erreur lors de l'ajout du quiz.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Ajouter un Quiz</h2>

        <?php if (isset($message)) : ?>
            <div class="alert alert-warning"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="add_quiz.php" method="post">
            <div class="mb-3">
                <label for="quiz_title" class="form-label">Titre du Quiz :</label>
                <input type="text" class="form-control" id="quiz_title" name="quiz_title" required>
            </div>

            <div class="mb-3">
                <label for="quiz_description" class="form-label">Description :</label>
                <textarea class="form-control" id="quiz_description" name="quiz_description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="course_id" class="form-label">Sélectionner un Cours :</label>
                <select class="form-select" id="course_id" name="course_id" required>
                    <option value="">Sélectionnez un cours</option>
                    <?php while ($course = mysqli_fetch_assoc($courses_result)) : ?>
                        <option value="<?php echo $course['course_id']; ?>"><?php echo $course['title']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" name="ajouter_quiz" class="btn btn-primary">Ajouter Quiz</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>