<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si le formateur est connecté
$teacher_id = $_SESSION['teacher_id'] ?? null;
if (!$teacher_id) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    $query = "INSERT INTO forum_categories (title, description) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $title, $description);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: gestion_categories.php');
        exit;
    } else {
        echo "Erreur lors de l'ajout de la catégorie.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une catégorie</title>
    <link rel="stylesheet" href="../css/style.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h1>Ajouter une catégorie de forum</h1>

    <form action="ajouter_categorie.php" method="post">
        <label for="title">Titre :</label>
        <input type="text" name="title" id="title" required>

        <label for="description">Description :</label>
        <textarea name="description" id="description" required></textarea>

        <button type="submit">Ajouter</button>
    </form>

    <a href="gestion_categories.php">Retour à la gestion des catégories</a>

    <?php include '../includes/footer.php'; ?>
</body>
</html>