<?php
include('../config/db.php');
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    echo "Quiz non spécifié.";
    exit;
}

// traitement du formulaire
if (isset($_POST['terminer']) || isset($_POST['ajouter_question'])) {
    $question = $_POST['question'];
    $answers = $_POST['answers'];
    $correct_answer = $_POST['correct'];

    // insertion de la question
    $stmt = mysqli_prepare($conn, "INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "is", $quiz_id, $question);
    mysqli_stmt_execute($stmt);
    $question_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // insertion des réponses
    foreach ($answers as $index => $answer_text) {
        $is_correct = ($correct_answer == $index) ? 1 : 0;
        $stmt = mysqli_prepare($conn, "INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "isi", $question_id, $answer_text, $is_correct);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // message
    if (isset($_POST['terminer'])) {
        $message = "Dernière question ajoutée avec succès ! Vous pouvez maintenant quitter.";
    } else {
        $message = "Question ajoutée avec succès !";
    }

    // vider les champs si souhaité (optionnel)
    $_POST = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter des Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Ajouter une question au quiz</h3>

    <?php if (isset($message)) : ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="question" class="form-label">Question</label>
            <textarea name="question" class="form-control" required><?php echo $_POST['question'] ?? ''; ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Réponses</label>
            <?php for ($i = 0; $i < 4; $i++) : ?>
                <div class="input-group mb-2">
                    <div class="input-group-text">
                        <input type="radio" name="correct" value="<?php echo $i; ?>" required 
                        <?php if (isset($_POST['correct']) && $_POST['correct'] == $i) echo 'checked'; ?>>
                    </div>
                    <input type="text" name="answers[]" class="form-control" placeholder="Réponse <?php echo $i + 1; ?>"
                    value="<?php echo $_POST['answers'][$i] ?? ''; ?>" required>
                </div>
            <?php endfor; ?>
            <small class="text-muted">Cochez la bonne réponse</small>
        </div>

        <button type="submit" name="ajouter_question" class="btn btn-success">Ajouter une autre question</button>
        <button type="submit" name="terminer" class="btn btn-secondary">Terminer</button>
    </form>
</div>
</body>
</html>