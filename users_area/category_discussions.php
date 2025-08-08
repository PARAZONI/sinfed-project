<?php
include('../config/db.php');

// Vérifie si un ID de catégorie est passé via l'URL
if (!isset($_GET['category_id']) || empty($_GET['category_id'])) {
    echo "Catégorie introuvable.";
    exit();
}

$category_id = intval($_GET['category_id']); // Récupère l'ID de la catégorie depuis l'URL

// Prépare la requête pour récupérer les discussions de cette catégorie
$query = "SELECT * FROM discussions WHERE category_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $category_id);
mysqli_stmt_execute($stmt);
$discussions_result = mysqli_stmt_get_result($stmt);

echo "<h1>Discussions dans cette catégorie</h1>";

if (mysqli_num_rows($discussions_result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($discussions_result)) {
        echo "<li><a href='discussion_details.php?discussion_id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></li>";
    }
    echo "</ul>";
} else {
    echo "<p>Aucune discussion trouvée dans cette catégorie.</p>";
}

mysqli_stmt_close($stmt);
?>