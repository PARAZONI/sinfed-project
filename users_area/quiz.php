<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification de la connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Vérification de l'ID du quiz passé en paramètre
if (!isset($_GET['quiz_id'])) {
    die("ID du quiz non spécifié.");
}

$quiz_id = $_GET['quiz_id'];

// Récupération des informations du quiz
$quiz_query = "SELECT title, description FROM quizzes WHERE quiz_id = ?";
$quiz_stmt = mysqli_prepare($conn, $quiz_query);
mysqli_stmt_bind_param($quiz_stmt, "i", $quiz_id);
mysqli_stmt_execute($quiz_stmt);
$quiz_result = mysqli_stmt_get_result($quiz_stmt);
$quiz = mysqli_fetch_assoc($quiz_result);

// Récupération des questions du quiz
$questions_query = "SELECT * FROM quiz_questions WHERE quiz_id = ?";
$questions_stmt = mysqli_prepare($conn, $questions_query);
mysqli_stmt_bind_param($questions_stmt, "i", $quiz_id);
mysqli_stmt_execute($questions_stmt);
$questions_result = mysqli_stmt_get_result($questions_stmt);

// Traitement du formulaire de soumission des réponses
if (isset($_POST['submit_answers'])) {
    $score = 0;
    $total_questions = mysqli_num_rows($questions_result);

    // Rewind le résultat des questions pour les analyser après soumission
    mysqli_data_seek($questions_result, 0); // Réinitialise le pointeur à 0

    while ($question = mysqli_fetch_assoc($questions_result)) {
        // Récupérer la réponse soumise par l'utilisateur pour chaque question
        if (isset($POST['question' . $question['question_id']])) {
            $user_answer = $POST['question' . $question['question_id']];

            // Vérifier si la réponse est correcte
            if (strtoupper($user_answer) === $question['correct_option']) {
                $score++;
            }
        }
    }

    // Enregistrer le score dans la base de données (optionnel)
    // $score_query = "INSERT INTO scores (user_id, quiz_id, score) VALUES (?, ?, ?)";
    // $score_stmt = mysqli_prepare($conn, $score_query);
    // mysqli_stmt_bind_param($score_stmt, "iii", $user_id, $quiz_id, $score);
    // mysqli_stmt_execute($score_stmt);

    // Afficher le score
    echo "<div class='alert alert-success'>Vous avez obtenu $score sur $total_questions !</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - <?php echo $quiz['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2><?php echo $quiz['title']; ?></h2>
        <p><?php echo $quiz['description']; ?></p>

        <form action="quiz.php?quiz_id=<?php echo $quiz_id; ?>" method="post">
            <?php while ($question = mysqli_fetch_assoc($questions_result)) : ?>
                <div class="mb-3">
                    <h5><?php echo $question['question_text']; ?></h5>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question_<?php echo $question['question_id']; ?>" value="A" required>
                        <label class="form-check-label" for="question_<?php echo $question['question_id']; ?>_A">
                            <?php echo $question['option_a']; ?>
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question_<?php echo $question['question_id']; ?>" value="B" required>
                        <label class="form-check-label" for="question_<?php echo $question['question_id']; ?>_B">
                            <?php echo $question['option_b']; ?>
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question_<?php echo $question['question_id']; ?>" value="C" required>
                        <label class="form-check-label" for="question_<?php echo $question['question_id']; ?>_C">
                            <?php echo $question['option_c']; ?>
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question_<?php echo $question['question_id']; ?>" value="D" required>
                        <label class="form-check-label" for="question_<?php echo $question['question_id']; ?>_D">
                            <?php echo $question['option_d']; ?>
                        </label>
                    </div>
                </div>
            <?php endwhile; ?>

            <button type="submit" name="submit_answers" class="btn btn-primary">Soumettre les réponses</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>