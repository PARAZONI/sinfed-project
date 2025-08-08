<?php
include('../Config/db.php');
session_start();

// Vérification de l'accès : si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer l'ID de l'utilisateur
$user_id = $_SESSION['user_id'];

// Récupérer et insérer le forum
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    
    if (!empty($title)) {
        // Insertion dans la base de données
        $query = "INSERT INTO forum (title) VALUES (?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $title);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Rediriger vers la liste des forums après la création
        header("Location: forum_list.php");
        exit();
    } else {
        $error = "Le titre du forum ne peut pas être vide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Forum</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Ajout de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Créer un Nouveau Forum</h2>

    <!-- Bouton retour avec icône -->
    <a href="forum_list.php" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Retour à la liste des forums
    </a>

    <!-- Affichage de l'erreur, si présente -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Titre du Forum</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Créer le Forum
        </button>
    </form>
</div>

</body>
</html>

<?php
mysqli_close($conn);
?>