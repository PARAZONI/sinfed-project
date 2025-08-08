<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : null;

if (!$quiz_id) {
    echo "<div class='container mt-5 alert alert-danger'>Quiz non spécifié.</div>";
    exit;
}

$check_query = "SELECT id FROM quizzes WHERE id = ?";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "i", $quiz_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) === 0) {
    echo "<div class='container mt-5 alert alert-warning'>Quiz introuvable.</div>";
    exit;
}

$query = "SELECT q.id AS question_id, q.question_text, a.id AS answer_id, a.answer_text 
          FROM questions q 
          JOIN answers a ON q.id = a.question_id 
          WHERE q.quiz_id = ? 
          ORDER BY q.id";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $quiz_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions[$row['question_id']]['text'] = $row['question_text'];
    $questions[$row['question_id']]['answers'][] = [
        'id' => $row['answer_id'],
        'text' => $row['answer_text']
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Quiz - Mission SINFED</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .quiz-card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background-color: white;
            animation: fadeIn 0.6s ease-in-out;
        }
        .quiz-header {
            background: linear-gradient(90deg, #4e54c8, #8f94fb);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }
        .form-check-input:checked {
            background-color: #4e54c8;
            border-color: #4e54c8;
        }
        .btn-primary {
            background-color: #4e54c8;
            border: none;
        }
        .question-box {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }
        .question-box.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        #countdown {
            font-weight: bold;
            font-size: 18px;
        }
        #countdown.warning {
            color: red;
            font-size: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5 mb-5">
    <div class="quiz-card p-4">
        <div class="quiz-header text-center mb-4">
            <h2>Mission : Test de compétences</h2>
            <p>Bienvenue dans le centre de formation <strong>SINFED</strong>. Prépare-toi pour un challenge en condition réelle !</p>
            <div id="countdown">Temps restant : <span id="time">03:00</span></div>
        </div>

        <div class="d-flex align-items-center mb-4">
            <img src="https://img.icons8.com/fluency/96/teacher.png" class="me-3" alt="Coach" style="width: 60px;">
            <div><strong>Coach SINFED :</strong> Je suis là pour t’aider. Concentre-toi et relève le défi !</div>
        </div>

        <?php if (empty($questions)): ?>
            <div class="alert alert-info text-center">Aucune question trouvée pour ce quiz.</div>
        <?php else: ?>
            <form id="quizForm" action="submit_quiz.php" method="post">
                <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
                <div class="progress mb-4">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%">0%</div>
                </div>

                <?php $index = 0; foreach ($questions as $question_id => $q): ?>
                    <div class="question-box <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                        <h5 class="fw-semibold"><?php echo htmlspecialchars($q['text']); ?></h5>
                        <?php foreach ($q['answers'] as $answer): ?>
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       id="answer_<?php echo $answer['id']; ?>"
                                       name="responses[<?php echo $question_id; ?>]"
                                       value="<?php echo $answer['id']; ?>"
                                       required>
                                <label class="form-check-label" for="answer_<?php echo $answer['id']; ?>">
                                    <?php echo htmlspecialchars($answer['text']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-primary next-btn">Suivant</button>
                        </div>
                    </div>
                <?php $index++; endforeach; ?>

                <div class="text-center mt-4 d-none" id="submitDiv">
                    <button type="submit" class="btn btn-success btn-lg">Soumettre les réponses</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<!-- Sons -->
<audio id="selectSound" src="https://www.soundjay.com/buttons/sounds/button-29.mp3" preload="auto"></audio>
<audio id="alertSound" src="https://www.soundjay.com/button/beep-07.wav" preload="auto"></audio>
<audio id="finalSound" src="https://www.soundjay.com/button/beep-10.wav" preload="auto"></audio>

<script>
    const questionBoxes = document.querySelectorAll(".question-box");
    const nextButtons = document.querySelectorAll(".next-btn");
    const total = questionBoxes.length;
    let current = 0;

    nextButtons.forEach((btn, index) => {
        btn.addEventListener("click", () => {
            questionBoxes[index].classList.remove("active");
            if (index + 1 < total) {
                questionBoxes[index + 1].classList.add("active");
            }
            updateProgress(index + 1);
            if (index + 1 === total) {
                document.getElementById("submitDiv").classList.remove("d-none");
            }
        });
    });

    function updateProgress(currentIndex) {
        const percent = Math.floor((currentIndex / total) * 100);
        const bar = document.getElementById("progressBar");
        bar.style.width = percent + "%";
        bar.textContent = percent + "%";
    }

    // Son à chaque sélection
    const radios = document.querySelectorAll('input[type="radio"]');
    const selectSound = document.getElementById("selectSound");
    radios.forEach(radio => {
        radio.addEventListener("change", () => {
            selectSound.currentTime = 0;
            selectSound.play();
        });
    });

    // Timer
    let duration = 3 * 60; // 3 minutes
    const timeDisplay = document.getElementById("time");
    const countdown = document.getElementById("countdown");
    const form = document.getElementById("quizForm");
    const alertSound = document.getElementById("alertSound");
    const finalSound = document.getElementById("finalSound");

    const countdownInterval = setInterval(() => {
        const minutes = Math.floor(duration / 60);
        const seconds = duration % 60;
        timeDisplay.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        if (duration === 30) {
            countdown.classList.add("warning");
            alert("Attention ! Il ne reste que 30 secondes.");
            alertSound.play();
        }

        if (duration <= 0) {
            clearInterval(countdownInterval);
            countdown.textContent = "Temps écoulé !";
            finalSound.play();
            setTimeout(() => form.submit(), 1000);
        }

        duration--;
    }, 1000);
</script>
</body>
</html>