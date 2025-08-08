<?php
// Activer les erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclure la connexion à la base de données
include('../config/db.php');

// Requête pour récupérer toutes les discussions
$query = "SELECT id, question, created_at FROM discussions ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des discussions</title>
</head>
<body>
<?php include '../includes/header.php'; ?>

    <h1>Liste des discussions</h1>
    <a href="submit_question.php">Poser une question</a> <!-- Lien pour poser une question -->

    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        echo '<ul>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li>';
            echo '<a href="discussions.php?id=' . htmlspecialchars($row['id']) . '">';
            echo htmlspecialchars($row['question']);
            echo '</a>';
            echo ' - Publié le ' . htmlspecialchars($row['created_at']);
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo "<p>Aucune discussion trouvée.</p>";
    }

    // Fermer la connexion
    mysqli_close($conn);
    ?>
<?php include '../includes/footer.php'; ?>

</body>
</html>

<?php
while ($row = mysqli_fetch_assoc($result)) {
    echo '<li>';
    echo '<a href="discussions.php?id=' . htmlspecialchars($row['id']) . '">';
    echo htmlspecialchars($row['question']);
    echo '</a>';
    echo ' - <a href="repondre.php?id=' . htmlspecialchars($row['id']) . '">Répondre à cette discussion</a>';
    echo '</li>';
}
?>