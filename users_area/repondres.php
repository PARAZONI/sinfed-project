<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté et est un formateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'formateur') {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
include('../config/db.php');

// Vérifier si l'ID de la discussion est passé dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $discussion_id = (int)$_GET['id'];

    // Requête pour récupérer la question
    $query = "SELECT question FROM discussions WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $discussion_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $discussion = mysqli_fetch_assoc($result);

    if (!$discussion) {
        echo "Discussion introuvable.";
        exit();
    }
} else {
    echo "ID de la discussion manquant.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Répondre à la discussion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Répondre à la question : <?php echo htmlspecialchars($discussion['question']); ?></h1>

        <form method="POST" action="submit_response.php">
            <input type="hidden" name="discussion_id" value="<?php echo $discussion_id; ?>">
            <textarea name="response" class="form-control" rows="5" placeholder="Votre réponse ici"></textarea><br>
            <button type="submit" class="btn btn-primary">Envoyer la réponse</button>
        </form>

        <a href="gerer_questions.php" class="btn btn-secondary">Retour à la gestion des questions</a>
        <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Fermer la connexion
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>