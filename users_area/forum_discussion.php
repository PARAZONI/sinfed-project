<?php
include('../Config/db.php');
session_start();

// vérification de l'accès
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role']; // récupérer le rôle (student ou trainer)

// vérifier si l'id du forum est passé dans l'url
if (!isset($_GET['forum_id'])) {
    echo "forum non trouvé.";
    exit();
}

$forum_id = $_GET['forum_id'];

// récupérer les informations du forum
$query = "SELECT * FROM forum WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $forum_id);
mysqli_stmt_execute($stmt);
$forum_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($forum_result) == 0) {
    echo "forum introuvable.";
    exit();
}

$forum = mysqli_fetch_assoc($forum_result);

// récupération des messages avec distinction entre étudiants et formateurs
$query_posts = "SELECT fm.id, fm.message, fm.created_at, 
                   CASE 
                        WHEN fm.user_role = 'student' THEN s.first_name
                        WHEN fm.user_role = 'trainer' THEN t.first_name
                   END AS first_name,
                   CASE 
                        WHEN fm.user_role = 'student' THEN s.last_name
                        WHEN fm.user_role = 'trainer' THEN t.last_name
                   END AS last_name
                FROM forum_messages fm
                LEFT JOIN students s ON fm.user_id = s.id AND fm.user_role = 'student'
                LEFT JOIN trainers t ON fm.user_id = t.id AND fm.user_role = 'trainer'
                WHERE fm.forum_id = ?
                ORDER BY fm.created_at ASC";

$stmt_posts = mysqli_prepare($conn, $query_posts);
mysqli_stmt_bind_param($stmt_posts, "i", $forum_id);
mysqli_stmt_execute($stmt_posts);
$posts_result = mysqli_stmt_get_result($stmt_posts);

// poster un message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $insert_query = "INSERT INTO forum_messages (forum_id, user_id, user_role, message) VALUES (?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt_insert, "iiss", $forum_id, $user_id, $user_role, $message);
        mysqli_stmt_execute($stmt_insert);
        mysqli_stmt_close($stmt_insert);
        header("Location: forum_discussion.php?forum_id=$forum_id");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion - <?= htmlspecialchars($forum['title']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


    <style>
        /* Titre bien centré avec animation */
        .forum-title {
            font-size: 1.8rem;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

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

        /* Style des messages */
        .forum-post {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .forum-post:hover {
            transform: scale(1.02);
        }

        .forum-post .post-header {
            font-weight: bold;
        }

        .forum-post .post-body {
            margin-top: 5px;
        }

        .forum-post small {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .forum-post .post-footer {
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .forum-post .user-icon {
            margin-right: 8px;
        }

        /* Bouton retour bien aligné */
        .back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        /* Formulaire stylisé */
        .post-form {
            background-color: #f1f3f5;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .post-form textarea {
            resize: none;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="forum-title">
        <i class="fas fa-comments"></i> <?= htmlspecialchars($forum['title']) ?> <span class="dots"></span>
    </h2>

    <a href="forum_list.php" class="btn btn-secondary back-btn">
        <i class="fas fa-arrow-left"></i> Retour aux forums
    </a>

    <!-- Affichage des messages -->
    <div class="list-group mb-4">
        <?php while ($post = mysqli_fetch_assoc($posts_result)) : ?>
            <div class="forum-post">
                <div class="post-header">
                    <i class="fas fa-user user-icon"></i>
                    <?= htmlspecialchars($post['first_name']) . ' ' . htmlspecialchars($post['last_name']) ?>
                </div>
                <div class="post-body">
                    <p><?= nl2br(htmlspecialchars($post['message'])) ?></p>
                </div>
                <div class="post-footer">
                    <small>Posté le <?= date('d/m/Y à H:i', strtotime($post['created_at'])) ?></small>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Formulaire de publication de message -->
    <h4 class="text-center">Publier un message</h4>
    <form method="POST" class="post-form">
        <textarea name="message" class="form-control mb-2" placeholder="Écrivez votre message ici..." required></textarea>
        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-paper-plane"></i> Envoyer
        </button>
    </form>
</div>

</body>
</html>

<?php
mysqli_stmt_close($stmt_posts);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>