<?php
include('../config/db.php');

// Démarrage de la session et vérification de l'authentification
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['user_id'];

// Validation des entrées POST
$quiz_id = $_POST['quiz_id'] ?? null;
$responses = $_POST['responses'] ?? [];

if (!$quiz_id || empty($responses)) {
    echo "Quiz non spécifié ou aucune réponse soumise.";
    exit;
}

if (!filter_var($quiz_id, FILTER_VALIDATE_INT)) {
    echo "ID du quiz invalide.";
    exit;
}

foreach ($responses as $question_id => $answer_id) {
    if (!is_numeric($question_id) || !is_numeric($answer_id)) {
        echo "Réponse invalide détectée.";
        exit;
    }
}

// Fonction pour récupérer les détails d'une question et ses réponses
function getAnswerDetails($conn, $question_id, $selected_answer) {
    $query = "SELECT q.question_text, a.id AS answer_id, a.answer_text, a.is_correct 
              FROM questions q 
              JOIN answers a ON q.id = a.question_id 
              WHERE q.id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $question_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $question_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $question_data['text'] = $row['question_text'];
        $question_data['answers'][] = [
            'id'         => $row['answer_id'],
            'text'       => $row['answer_text'],
            'is_correct' => $row['is_correct'],
            'is_selected'=> ($row['answer_id'] == $selected_answer)
        ];
    }
    return $question_data;
}

// Calcul du score et préparation des détails
$score = 0;
$total = count($responses);
$details = [];

foreach ($responses as $question_id => $answer_id) {
    // Vérifier si la réponse est correcte
    $checkQuery = "SELECT is_correct FROM answers WHERE id = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "i", $answer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    if ($row && $row['is_correct']) {
        $score++;
    }
    
    // Récupérer les détails de la question
    $details[$question_id] = getAnswerDetails($conn, $question_id, $answer_id);
}

// Calcul du pourcentage de réussite
$percentage = round(($score / $total) * 100);

// Enregistrement du score dans la base de données
$stmt = mysqli_prepare($conn, "INSERT INTO quiz_results (student_id, quiz_id, score, total_questions) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "iiii", $student_id, $quiz_id, $score, $total);
mysqli_stmt_execute($stmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat du Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/howler@2.2.1/dist/howler.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #fdfbfb, #ebedee);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-info {
            background: #d0f0fd;
            border-left: 10px solid #00bfff;
            border-radius: 10px;
            box-shadow: 2px 2px 12px rgba(0,0,0,0.1);
        }

        .result-details .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-left: 5px solid #00bfff;
            border-radius: 12px;
            box-shadow: 1px 1px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            padding: 15px;
            transition: transform 0.2s;
        }

        .result-details .card:hover {
            transform: scale(1.01);
        }

        .result-details p {
            margin: 0.4rem 0;
        }

        .btn {
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 25px;
        }

        .btn-secondary {
            background-color: #888;
            border: none;
        }

        .btn-primary {
            background-color: #00bfff;
            border: none;
        }

        h5 {
            color: #333;
        }

        /* Styles pour animation de machine à écrire */
        .typewriter {
            overflow: hidden;
            border-right: .15em solid #00bfff;
            white-space: nowrap;
            margin: 0 auto;
            letter-spacing: .1em;
            animation: typing 4s steps(40) 1s 1 normal both, blinkCaret 0.75s step-end infinite;
        }

        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }

        @keyframes blinkCaret {
            50% { border-color: transparent; }
        }

        /* Feu d'artifice */
        .fireworks {
            position: absolute;
            top: 10%;
            left: 50%;
            z-index: 9999;
        }
        .fireworks img {
            width: 100px;
            animation: fireworkAnim 2s ease-in-out infinite;
        }

        @keyframes fireworkAnim {
            0% { transform: translateY(0) scale(0.5); opacity: 0; }
            50% { transform: translateY(-300px) scale(1); opacity: 1; }
            100% { transform: translateY(0) scale(0.5); opacity: 0; }
        }

    </style>
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-info text-center">
        <h4 class="mb-3 typewriter">✨ Résultat du Quiz ✨</h4>
        <p><strong>Score :</strong> <?php echo htmlspecialchars($score); ?> / <?php echo htmlspecialchars($total); ?></p>
        <p><strong>Taux de réussite :</strong> <?php echo htmlspecialchars($percentage); ?>%</p>
        <?php
            if ($score == $total) {
                echo "<div class='mt-3'><img src='../assets1/img/emoji/AREmoji_20231112_221255_729.gif' alt='Excellent' style='height:80px;'><p class='mt-2 fw-bold text-success'>Excellent ! Tu es un pro !</p></div>";
                echo "<div class='fireworks'>
                        <img src='../assets1/img/firework1.gif' alt='Feu d'artifice'>
                        <img src='../assets1/img/firework2.gif' alt='Feu d'artifice'>
                    </div>";
                echo "<audio autoplay><source src='../assets1/sounds/victory.mp3' type='audio/mp3'></audio>";
            } elseif ($score >= ($total / 2)) {
                echo "<div class='mt-3'><img src='../assets1/img/emoji/AREmoji_20231112_221252_218.gif' alt='Bon travail' style='height:80px;'><p class='mt-2 fw-bold text-primary'>Pas mal du tout !</p></div>";
            } else {
                echo "<div class='mt-3'><img src='../assets1/img/emoji/AREmoji_20231112_221253_299.gif' alt='Continue à t'entraîner' style='height:80px;'><p class='mt-2 fw-bold text-warning'>Courage ! Tu y arriveras !</p></div>";
                echo "<p class='fw-bold text-danger'>Pourquoi ne pas essayer un mini-jeu ?</p>";
                echo "<button id='startGame' class='btn btn-primary mt-3'>Lancer le mini-jeu</button>";

                // Mini-jeu : Petit jeu de mémoire
                echo "
                    <script>
                        document.getElementById('startGame').addEventListener('click', function() {
                            alert('Mini-jeu démarré ! Trouvez les paires de cartes.');
                        });
                    </script>";
            }
        ?>
    </div>

    <div class="result-details">
        <?php foreach ($details as $questionData): ?>
            <div class="card">
                <h5>❓ <?php echo htmlspecialchars($questionData['text']); ?></h5>
                <?php foreach ($questionData['answers'] as $a): ?>
                    <p style="
                        <?php
                            if ($a['is_correct']) {
                                echo 'color:green;font-weight:bold;';
                            } elseif ($a['is_selected']) {
                                echo 'color:red;font-weight:bold;';
                            } else {
                                echo 'color:gray;';
                            }
                        ?>
                    ">
                        <?php
                            if ($a['is_correct']) {
                                echo '✅ ';
                            } elseif ($a['is_selected']) {
                                echo '❌ ';
                            } else {
                                echo '• ';
                            }
                        ?>
                        <?php echo htmlspecialchars($a['text']); ?>
                        <?php
                            if ($a['is_selected']) {
                                echo ' (Votre choix)';
                            }
                            if ($a['is_correct']) {
                                echo ' (Bonne réponse)';
                            }
                        ?>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mb-4">
        <a href="mes_quiz.php" class="btn btn-secondary me-2">↩️ Retour aux quiz</a>
        <a href="take_quiz.php?quiz_id=<?php echo urlencode($quiz_id); ?>" class="btn btn-primary">🔄 Rejouer le quiz</a>
    </div>
</div>

</body>
</html>