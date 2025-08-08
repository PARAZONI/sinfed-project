<?php
include('../config/db.php'); // Assurez-vous que ce fichier connecte correctement à $conn

if (isset($_POST['question']) && !empty($_POST['question'])) {
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $query = "INSERT INTO discussions (question, status, created_at) VALUES ('$question', 'open', NOW())";

    if (mysqli_query($conn, $query)) {
        echo "Question ajoutée avec succès.";
    } else {
        echo "Erreur : " . mysqli_error($conn);
    }
} else {
    echo "La question est vide.";
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poser une question</title>
</head>
<body>
    <h1>Poser une question</h1>
    <form action="submit_question.php" method="POST">
        <label for="question">Votre question :</label><br>
        <textarea id="question" name="question" rows="4" cols="50" required></textarea><br><br>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>