<?php
session_start();
include('../config/db.php');
 // ta connexion DB

// Traitement de l'ajout
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_option = $_POST['correct_option'];

    $stmt = $con->prepare("INSERT INTO quiz_questions (course_id, question, option1, option2, option3, option4, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $course_id, $question, $option1, $option2, $option3, $option4, $correct_option);

    if ($stmt->execute()) {
        $message = "Question ajoutée avec succès.";
    } else {
        $message = "Erreur lors de l'ajout de la question.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Quiz - SINFED Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7fc;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .btn-submit {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h3 class="text-center mb-4">Ajouter une Question de Quiz</h3>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <!-- Sélection du cours -->
            <div class="mb-3">
                <label class="form-label">Cours associé</label>
                <select class="form-select" name="course_id" required>
                    <option value="">-- Choisir un cours --</option>
                    <?php
                    $result = mysqli_query($con, "SELECT * FROM courses");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['id']}'>{$row['course_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Question -->
            <div class="mb-3">
                <label class="form-label">Question</label>
                <textarea class="form-control" name="question" rows="3" required></textarea>
            </div>

            <!-- Réponses -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Option 1</label>
                    <input type="text" class="form-control" name="option1" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Option 2</label>
                    <input type="text" class="form-control" name="option2" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Option 3</label>
                    <input type="text" class="form-control" name="option3" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Option 4</label>
                    <input type="text" class="form-control" name="option4" required>
                </div>
            </div>

            <!-- Réponse correcte -->
            <div class="mb-3">
                <label class="form-label">Bonne réponse</label>
                <select class="form-select" name="correct_option" required>
                    <option value="">-- Sélectionner la bonne réponse --</option>
                    <option value="option1">Option 1</option>
                    <option value="option2">Option 2</option>
                    <option value="option3">Option 3</option>
                    <option value="option4">Option 4</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success btn-submit">Ajouter la question</button>
        </form>
    </div>
</div>

</body>
</html>