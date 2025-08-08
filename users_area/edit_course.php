<?php
include('../config/db.php');

// Démarrer la session
session_start();

// Vérifier si le formateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'trainer') {
    echo "Veuillez vous connecter en tant que formateur.";
    exit();
}

// Si un cours est sélectionné
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $course_id = $_GET['id'];
} else {
    // Aucune sélection, on récupère tous les cours disponibles
    $course_id = null;
}

// Récupérer tous les cours du formateur
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM courses WHERE trainer_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $trainer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    // Préparer la requête pour mettre à jour le cours
    $update_query = "UPDATE courses SET title = ?, description = ?, start_date = ?, end_date = ?, status = ? WHERE course_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "sssssi", $title, $description, $start_date, $end_date, $status, $course_id);

    if (mysqli_stmt_execute($update_stmt)) {
        echo "<p>Cours mis à jour avec succès.</p>";
    } else {
        echo "<p>Erreur lors de la mise à jour du cours.</p>";
    }

    // Fermer la requête de mise à jour
    mysqli_stmt_close($update_stmt);
}

// Si un cours est sélectionné, récupérer ses détails
if ($course_id !== null) {
    $query = "SELECT * FROM courses WHERE course_id = ? AND trainer_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $course_id, $trainer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $course = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    $course = null;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer le cours</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


</head>
<body>
<a href="../users_area/trainer_home.php" class="btn btn-primary">
    <i class="fas fa-arrow-left"></i> Retour
</a>
    <h1>Modifier un cours</h1>

    <?php if ($course === null): ?>
        <form method="POST" action="">
            <label for="course_id">Choisissez un cours à modifier :</label><br>
            <select name="course_id" id="course_id" required>
                <option value="">Sélectionner un cours</option>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <option value="<?php echo $row['course_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></option>
                <?php endwhile; ?>
            </select><br><br>
            <input type="submit" value="Choisir le cours">
        </form>
    <?php else: ?>
        <form method="POST" action="">
            <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">

            <label for="title">Titre du cours :</label><br>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required><br><br>

            <label for="description">Description :</label><br>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($course['description']); ?></textarea><br><br>

            <label for="start_date">Date de début :</label><br>
            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($course['start_date']); ?>" required><br><br>

            <label for="end_date">Date de fin :</label><br>
            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($course['end_date']); ?>"><br><br>

            <label for="status">Statut :</label><br>
            <select id="status" name="status" required>
                <option value="planifié" <?php if ($course['status'] == 'planifié') echo 'selected'; ?>>Planifié</option>
                <option value="en cours" <?php if ($course['status'] == 'en cours') echo 'selected'; ?>>En cours</option>
                <option value="terminé" <?php if ($course['status'] == 'terminé') echo 'selected'; ?>>Terminé</option>
            </select><br><br>

            <input type="submit" value="Mettre à jour">
        </form>
    <?php endif; ?>

    <p><a href="../users_area/trainer_home.php">Retour à la page d'accueil du formateur</a></p>
</body>
</html>

<?php
// // Fermer la requête de sélection
// mysqli_stmt_close($stmt);
?>