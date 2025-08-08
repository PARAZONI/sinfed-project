<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) { // Vérification basée sur user_id
    header('Location: ../users_area/login.php');
    exit();
}

// Inclure la configuration de la base de données
include('../config/db.php');
if (!$conn) {
    die("Erreur de connexion à la base de données");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Discussion</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Remplacez par votre fichier CSS -->
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

</head>
<body>
    <?php include('../includes/header.php'); ?>

    <main class="container mt-4">
        <h1>Créer une Nouvelle Discussion</h1>
        <!-- Formulaire de création de discussion -->
        <form action="process_create_discussion.php" method="POST">
    <div class="mb-3">
        <label for="question" class="form-label">Votre question</label>
        <textarea class="form-control" id="question" name="question" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Publier</button>
</form>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>
</html>