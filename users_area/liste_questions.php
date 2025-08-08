<?php include '../includes/header.php'; ?>

<?php
// Activer les erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
include('../config/db.php');

// Requête pour récupérer toutes les questions
$query = "SELECT id, question, created_at FROM discussions ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<h1>Liste des questions posées</h1>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Question</th><th>Date</th><th>Action</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['question']) . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "<td><a href='repondre.php?id=" . $row['id'] . "'>Répondre</a></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>Aucune question n'a été posée.</p>";
}

// Fermer la connexion
mysqli_close($conn);
?>
<?php include '../includes/footer.php'; ?>
