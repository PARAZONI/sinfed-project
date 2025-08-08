<?php
include('../config/db.php');

// Requête pour récupérer les discussions
$query = "SELECT * FROM discussions ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div>';
        echo '<h3>' . htmlspecialchars($row['question']) . '</h3>';
        echo '<p>Statut : ' . htmlspecialchars($row['status']) . '</p>';
        echo '<a href="repondre.php?id=' . $row['id'] . '">Répondre</a>';
        echo '</div><hr>';
    }
} else {
    echo "<p>Aucune discussion trouvée.</p>";
}

mysqli_close($conn);
?>