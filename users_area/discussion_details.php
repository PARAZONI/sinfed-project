<?php
include('../config/db.php');
session_start();

// Vérifie si un ID de discussion est passé dans l'URL
if (!isset($_GET['discussion_id']) || empty($_GET['discussion_id'])) {
    echo "Discussion introuvable.";
    exit();
}

$discussion_id = intval($_GET['discussion_id']);

// Préparer la requête pour récupérer les détails de la discussion
$query = "SELECT * FROM discussions WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $discussion_id);
mysqli_stmt_execute($stmt);
$discussion_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($discussion_result) > 0) {
    $discussion = mysqli_fetch_assoc($discussion_result);
    echo "<h1>" . htmlspecialchars($discussion['title']) . "</h1>";
    echo "<p>" . htmlspecialchars($discussion['content']) . "</p>";
    echo "<p>Créée le : " . htmlspecialchars($discussion['created_at']) . "</p>";
} else {
    echo "La discussion n'existe pas.";
    exit();
}

mysqli_stmt_close($stmt);

// Afficher les réponses à la discussion
$query_responses = "SELECT r.*, u.username FROM responses r 
                    JOIN users u ON r.user_id = u.id 
                    WHERE r.discussion_id = ? ORDER BY r.created_at ASC";
$stmt_responses = mysqli_prepare($conn, $query_responses);
mysqli_stmt_bind_param($stmt_responses, "i", $discussion_id);
mysqli_stmt_execute($stmt_responses);
$responses_result = mysqli_stmt_get_result($stmt_responses);

echo "<h2>Réponses :</h2>";

if (mysqli_num_rows($responses_result) > 0) {
    while ($response = mysqli_fetch_assoc($responses_result)) {
        echo "<div>
                <p><strong>" . htmlspecialchars($response['username']) . "</strong> : " . htmlspecialchars($response['content']) . "</p>
                <p>Posté le : " . htmlspecialchars($response['created_at']) . "</p>
              </div>";
    }
} else {
    echo "<p>Aucune réponse à cette discussion.</p>";
}

mysqli_stmt_close($stmt_responses);
?>