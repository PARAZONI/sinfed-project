<?php
// Activer les erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Démarrer la session
session_start();

// Connexion à la base de données
include('../config/db.php');

// Vérifier si l'ID de la discussion est fourni dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $discussion_id = (int)$_GET['id']; // Sécuriser l'ID

    // Requête pour récupérer les détails de la discussion
    $query = "SELECT question FROM discussions WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $discussion_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $discussion = mysqli_fetch_assoc($result);
            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Répondre à une discussion</title>
                <link rel="stylesheet" href="../assets/css/style.css"> <!-- Lien vers vos styles CSS -->
            </head>
            <body>
                <div class="container">
                    <h1>Répondre à la discussion :</h1>
                    <p><strong>Question :</strong> <?php echo htmlspecialchars($discussion['question']); ?></p>

                    <form method="post" action="submit_response.php">
                        <!-- Champ caché pour l'ID de la discussion -->
                        <input type="hidden" name="discussion_id" value="<?php echo $discussion_id; ?>">
                        <div>
                            <label for="response">Votre réponse :</label><br>
                            <textarea name="response" id="response" rows="5" cols="50" placeholder="Écrivez votre réponse ici..." required></textarea>
                        </div>
                        <br>
                        <button type="submit">Envoyer la réponse</button>
                    </form>
                </div>
            </body>
            </html>
            <?php
        } else {
            // Si l'ID n'existe pas
            echo "<p>Discussion introuvable.</p>";
            echo '<a href="discussions.php">Retour à la liste des discussions</a>';
        }

        // Fermer la requête
        mysqli_stmt_close($stmt);
    } else {
        echo "Erreur dans la requête : " . mysqli_error($conn);
    }
} else {
    // Si l'ID est manquant dans l'URL
    echo "<p>ID de discussion manquant.</p>";
    echo '<a href="discussions.php">Retour à la liste des discussions</a>';
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>