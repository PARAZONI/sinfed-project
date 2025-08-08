<?php
include('../config/db.php');

// Vérifie si la réponse a été soumise
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['discussion_id']) && isset($_POST['reply_content']) && !empty($_POST['reply_content'])) {
        $discussion_id = intval($_POST['discussion_id']);
        $reply_content = mysqli_real_escape_string($conn, $_POST['reply_content']);
        $author_name = "Nom de l'utilisateur"; // Remplacer par le nom de l'utilisateur connecté, si nécessaire

        // Prépare la requête pour insérer la réponse dans la base de données
        $query = "INSERT INTO replies (discussion_id, content, author_name, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iss", $discussion_id, $reply_content, $author_name);

        if (mysqli_stmt_execute($stmt)) {
            echo "Réponse envoyée avec succès.";
            header("Location: discussion_details.php?discussion_id=" . $discussion_id); // Redirige vers les détails de la discussion après la soumission
            exit();
        } else {
            echo "Erreur lors de l'envoi de la réponse.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Le contenu de la réponse ne peut pas être vide.";
    }
}
?>