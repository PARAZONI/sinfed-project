<?php
include('../config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['quiz_id'])) {
    header("Location: dashboard.php");
    exit;
}

$quiz_id = $_GET['quiz_id'];

// récupérer les infos du quiz
$quiz_query = "SELECT title FROM quizzes WHERE quiz_id = ?";
$stmt_quiz = mysqli_prepare($conn, $quiz_query);
mysqli_stmt_bind_param($stmt_quiz, "i", $quiz_id);
mysqli_stmt_execute($stmt_quiz);
$quiz_result = mysqli_stmt_get_result($stmt_quiz);
$quiz = mysqli_fetch_assoc($quiz_result);

// récupérer les réponses de l'élève
$query = "
    SELECT q.question_text, q.correct_answer, ua.answer AS student_answer
    FROM user_answers ua
    JOIN questions q ON ua.question_id = q.question_id
    WHERE ua.user_id = ? AND ua.quiz_id = ?
";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $quiz_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$score = 0;
$total = 0;
$responses = [];

while ($row = mysqli_fetch_assoc($result)) {
    $total++;
    $correct = strtolower(trim($row['correct_answer'])) === strtolower(trim($row['student_answer']));
    if ($correct) $score++;

    $responses[] = [
        'question' => $row['question_text'],
        'correct_answer' => $row['correct_answer'],
        'student_answer' => $row['student_answer'],
        'is_correct' => $correct
    ];
}

$note_sur_100 = ($total > 0) ? round(($score / $total) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat du Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .correct { background-color: #d4edda; }
        .incorrect { background-color: #f8d7da; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Résultat - <?php echo htmlspecialchars($quiz['title']); ?></h2>
    <div class="alert alert-info">
        Score : <strong><?php echo $score; ?>/<?php echo $total; ?></strong> — Note : <strong><?php echo $note_sur_100; ?>/100</strong>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Question</th>
                <th>Votre réponse</th>
                <th>Bonne réponse</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($responses as $response) : ?>
            <tr class="<?php echo $response['is_correct'] ? 'correct' : 'incorrect'; ?>">
                <td><?php echo htmlspecialchars($response['question']); ?></td>
                <td><?php echo htmlspecialchars($response['student_answer']); ?></td>
                <td><?php echo htmlspecialchars($response['correct_answer']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>
</div>
</body>
</html>