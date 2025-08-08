<?php
// Activer les erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
include('../config/db.php');

// Vérifier si les données sont envoyées via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si l'ID et la réponse sont fournis
    if (isset($_POST['discussion_id'], $_POST['response']) && !empty($_POST['response'])) {
        $discussion_id = (int)$_POST['discussion_id'];
        $response = mysqli_real_escape_string($conn, $_POST['response']);

        // Requête pour insérer la réponse
        $query = "INSERT INTO responses (discussion_id, response, created_at) VALUES (?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "is", $discussion_id, $response);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "<p>Réponse envoyée avec succès.</p>";
                echo '<a href="discussions.php">Retour à la liste des discussions</a>';
            } else {
                echo "<p>Erreur lors de l'envoi de la réponse.</p>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Erreur dans la requête : " . mysqli_error($conn);
        }
    } else {
        echo "<p>Veuillez entrer une réponse valide.</p>";
    }
} else {
    echo "<p>Requête invalide.</p>";
}

// Fermer la connexion
mysqli_close($conn);
?>