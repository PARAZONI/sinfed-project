<?php
include('../Config/db.php');
session_start();

// Vérification de l'accès
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier le rôle de l'utilisateur pour rediriger correctement
$return_url = ($_SESSION['user_role'] == 'student') ? 'student_home.php' : 'trainer_home.php';

// Récupérer la liste des forums
$query = "SELECT id, title, created_at FROM forum ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Forums</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Ajout de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


    <style>
        /* Ajout d'une image de fond */
        body {
            background-image: url('../assets1/image_forum_list.jpeg'); /* Remplacez par le chemin de votre image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white; /* Assurez-vous que le texte soit visible sur l'image */
            font-family: Arial, sans-serif;
        }

        /* Centrage du titre avec icône */
        .forum-title {
            font-size: 1.8rem;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px; /* Espacement entre l'icône et le texte */
            margin-bottom: 20px;
        }

        /* Animation des points */
        .forum-title .dots::after {
            content: '.';
            font-size: 1.8rem;
            animation: dotsAnimation 1.5s infinite steps(1);
        }

        @keyframes dotsAnimation {
            0% { content: '.'; }
            33% { content: '..'; }
            66% { content: '...'; }
            100% { content: '.'; }
        }

        /* Boutons bien espacés */
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        /* Animation des liens */
        .list-group-item {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        /* Conteneur de contenu avec un fond légèrement opaque pour une meilleure lisibilité */
        /* .container {
            background: rgba(0, 0, 0, 0.5); 
            padding: 30px;
            border-radius: 10px;
        } */
                    /* Fond sombre transparent */

    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="forum-title">
        <i class="fas fa-comments"></i> Liste des Forums <span class="dots"></span>
    </h2>

    <!-- Conteneur des boutons bien centrés -->
    <div class="btn-container">
        <a href="forum_create.php" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Créer un Forum
        </a>

        <!-- Bouton "Retour à l'accueil" qui redirige selon le rôle de l'utilisateur -->
        <a href="<?= $return_url ?>" class="btn btn-secondary">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>

    </div>

    <div class="list-group">
        <?php while ($forum = mysqli_fetch_assoc($result)) : ?>
            <a href="forum_discussion.php?forum_id=<?= $forum['id'] ?>" class="list-group-item list-group-item-action">
                <i class="fas fa-comments"></i> <?= htmlspecialchars($forum['title']) ?>
                <small class="text-muted">Créé le <?= date('d/m/Y', strtotime($forum['created_at'])) ?></small>
            </a>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>

<?php
mysqli_close($conn);
?>