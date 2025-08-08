<?php
include('../config/db.php');
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['user_id'];

// Quiz déjà faits
$query_done = "SELECT quiz_id FROM quiz_results WHERE student_id = ? AND status = 'done'";
$stmt_done = mysqli_prepare($conn, $query_done);
mysqli_stmt_bind_param($stmt_done, "i", $student_id);
mysqli_stmt_execute($stmt_done);
$result_done = mysqli_stmt_get_result($stmt_done);

$quiz_done = [];
while ($row = mysqli_fetch_assoc($result_done)) {
    $quiz_done[] = $row['quiz_id'];
}

// Tous les quiz avec infos
$query = "SELECT q.id AS quiz_id, q.title AS quiz_title, q.description, c.title AS course_title, u.first_name, u.last_name
          FROM quizzes q
          JOIN courses c ON q.course_id = c.course_id
          JOIN users u ON q.formateur_id = u.id
          ORDER BY q.id DESC";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Erreur de requête : " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #fdfbfb, #ebedee);
            transition: background 0.3s ease;
        }

        .dark-mode {
            background: #121212;
            color: #ffffff;
        }

        h2 {
            font-weight: bold;
            margin-bottom: 40px;
            color: #333;
            transition: color 0.3s ease;
        }

        .dark-mode h2 {
            color: #fff;
        }

        .quiz-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease-in-out;
        }

        .quiz-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .dark-mode .quiz-card {
            background: #333;
            color: #fff;
        }

        .card-title {
            color: #3a3a3a;
            font-size: 1.3rem;
        }

        .dark-mode .card-title {
            color: #fff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .badge.bg-success {
            background-color: #28a745;
            font-size: 0.9rem;
            padding: 5px 10px;
        }

        .card-text {
            color: #555;
        }

        .dark-mode .card-text {
            color: #ccc;
        }

        .quiz-meta {
            font-size: 0.9rem;
            color: #777;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border-radius: 8px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .dark-mode .alert-warning {
            background-color: #333;
            color: #ccc;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center"><i class="fas fa-question-circle"></i> Liste des Quiz Disponibles</h2>

    <?php if (mysqli_num_rows($result) == 0) : ?>
        <div class="alert alert-warning text-center">Aucun quiz disponible pour le moment.</div>
    <?php endif; ?>

    <div class="row">
        <?php while ($quiz = mysqli_fetch_assoc($result)) : ?>
            <div class="col-md-4 mb-4">
                <div class="card quiz-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-lightbulb"></i> 
                            <?php echo htmlspecialchars($quiz['quiz_title']); ?>
                        </h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($quiz['description'])); ?></p>
                        <p class="quiz-meta">
                            <i class="fas fa-book"></i> Cours : <?php echo htmlspecialchars($quiz['course_title']); ?><br>
                            <i class="fas fa-chalkboard-teacher"></i> Formateur : <?php echo htmlspecialchars($quiz['first_name'] . ' ' . $quiz['last_name']); ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <?php if (in_array($quiz['quiz_id'], $quiz_done)) : ?>
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Terminé</span>
                            <?php else : ?>
                                <a href="take_quiz.php?quiz_id=<?php echo $quiz['quiz_id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-play"></i> Commencer
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Dark Mode Toggle -->
<div class="position-fixed bottom-0 end-0 p-3">
    <button id="darkModeToggle" class="btn btn-dark">Activer le Dark Mode</button>
</div>

<script>
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    
    // Vérifier si le mode sombre est activé
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
        darkModeToggle.textContent = 'Désactiver le Dark Mode';
    }

    // Basculer le mode sombre
    darkModeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
            darkModeToggle.textContent = 'Désactiver le Dark Mode';
        } else {
            localStorage.setItem('darkMode', 'disabled');
            darkModeToggle.textContent = 'Activer le Dark Mode';
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>