<?php
include('../config/db.php');
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Veuillez vous connecter pour répondre à une discussion.";
    exit();
}

// Vérifie si un ID de discussion est passé dans l'URL
if (!isset($_GET['discussion_id']) || empty($_GET['discussion_id'])) {
    echo "Discussion introuvable.";
    exit();
}

$discussion_id = intval($_GET['discussion_id']);
$user_id = $_SESSION['user_id'];  // ID de l'utilisateur connecté

// Préparer la requête pour récupérer les détails de la discussion
$query = "SELECT * FROM discussions WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $discussion_id);
mysqli_stmt_execute($stmt);
$discussion_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($discussion_result) > 0) {
    $discussion = mysqli_fetch_assoc($discussion_result);
    echo "<h1>Répondre à la discussion : " . htmlspecialchars($discussion['title']) . "</h1>";
    echo "<p>" . htmlspecialchars($discussion['content']) . "</p>";
} else {
    echo "La discussion n'existe pas.";
    exit();
}

mysqli_stmt_close($stmt);

// Traitement de la réponse
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response_content = htmlspecialchars($_POST['response']);
    $insert_query = "INSERT INTO responses (discussion_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())";
    $stmt_insert = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt_insert, "iis", $discussion_id, $user_id, $response_content);

    if (mysqli_stmt_execute($stmt_insert)) {
        echo "Votre réponse a été postée avec succès!";
        header("Location: discussion_detail.php?discussion_id=" . $discussion_id);
        exit();
    } else {
        echo "Erreur lors de l'envoi de la réponse.";
    }
    mysqli_stmt_close($stmt_insert);
}
?>

<!-- Formulaire de réponse -->
<form action="" method="POST">
    <textarea name="response" rows="5" cols="50" placeholder="Votre réponse..."></textarea><br>
    <button type="submit">Envoyer la réponse</button>
</form>