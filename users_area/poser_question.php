<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de login
    header("Location: login.php");
    exit();  // Arrêter l'exécution du script
}

// Connexion à la base de données
include('../config/db.php');

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = mysqli_real_escape_string($conn, $_POST['question']);

    // Insérer la question dans la base de données
    $query = "INSERT INTO discussions (question, user_id) VALUES ('$question', '".$_SESSION['user_id']."')";
    if (mysqli_query($conn, $query)) {
        echo "Question posée avec succès.";
    } else {
        echo "Erreur lors de l'enregistrement de la question.";
    }
}

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
    <form method="post" action="poser_question.php">
        <label for="question">Votre question :</label><br>
        <textarea id="question" name="question" rows="5" cols="50" placeholder="Écrivez votre question ici" required></textarea><br>
        <button type="submit">Poser la question</button>
    </form>
</body>
</html>