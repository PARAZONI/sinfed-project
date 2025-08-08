<?php
include('../config/db.php');
session_start();

// Vérifier si l'élève est connecté
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];  // Récupère l'ID de l'élève connecté

// Requête pour récupérer les cours inscrits
$query = "SELECT c.title, c.description, c.start_date, c.end_date FROM student_courses sc JOIN courses c ON sc.course_id = c.id WHERE sc.student_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

echo "<h1>Tableau de Bord</h1>";
echo "<h2>Cours Inscrits</h2>";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div>
                <h3>" . htmlspecialchars($row['title']) . "</h3>
                <p>" . htmlspecialchars($row['description']) . "</p>
                <p>Date de début : " . htmlspecialchars($row['start_date']) . "</p>
                <p>Date de fin : " . htmlspecialchars($row['end_date']) . "</p>
              </div>";
    }
} else {
    echo "<p>Aucun cours inscrit pour cet élève.</p>";
}

mysqli_stmt_close($stmt);
?>