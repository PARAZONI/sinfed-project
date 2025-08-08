<?php
include('../config/db.php');

session_start(); // Assure que la session est démarrée

// Assure que l'utilisateur est connecté
if (!isset($_SESSION['trainer_id'])) {
    echo "Aucun formateur connecté.";
    exit();
}

// Récupère l'ID du formateur connecté depuis la session
$trainer_id = $_SESSION['trainer_id'];

// Préparer et exécuter la requête pour récupérer les cours
$query = "SELECT * FROM courses WHERE trainer_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $trainer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Afficher les cours
echo "<h1>Mes Cours</h1>";

// Vérifie s'il y a des cours pour le formateur
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div>
                <h2>" . htmlspecialchars($row['title']) . "</h2>
                <p>" . htmlspecialchars($row['description']) . "</p>
                <p>Date de début : " . htmlspecialchars($row['start_date']) . "</p>
                <a href='edit_course.php?id=" . htmlspecialchars($row['course_id']) . "'>Modifier</a> |
                <a href='delete_course.php?id=" . htmlspecialchars($row['course_id']) . "' onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');\">Supprimer</a>
              </div>";
    }
} else {
    echo "<p>Aucun cours trouvé pour ce formateur.</p>";
}

// Fermer la requête
mysqli_stmt_close($stmt);
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Amatic+SC:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

<a href="../users_area/trainer_home.php" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> Retour
</a>
