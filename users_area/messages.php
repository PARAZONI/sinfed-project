<?php
include('../Config/db.php');
session_start();

// Vérification de l'accès
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../login.php');
    exit;
}

// Récupération des messages de l'élève
$query = "SELECT m.id, m.message, m.response, m.created_at, 
                 t.first_name AS trainer_first_name, t.last_name AS trainer_last_name, c.title
          FROM messages m
          JOIN courses c ON m.course_id = c.course_id
          JOIN trainers t ON c.trainer_id = t.id
          WHERE m.student_id = ?
          ORDER BY m.created_at DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$messages_result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages reçus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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

    <style>
        body {
            background-image: url('../assets1/image_voir_messages.jpeg'); /* Remplace par le chemin de ton image */
            background-size: cover; /* Assure-toi que l'image couvre toute la page */
            background-position: center center; /* Centre l'image */
            background-attachment: fixed; /* Rend l'image fixe lors du défilement */
            color: #333; /* Assure-toi que le texte reste lisible */
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Fond légèrement transparent pour que l'image soit visible derrière */
            padding: 20px;
            border-radius: 10px;
        }

        h2 {
            color: #FF5722; /* Couleur du titre */
        }

        .btn {
            background-color: #FF5722;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <a href="../users_area/send_message.php" class="btn">
        <i class="fas fa-arrow-left"></i> Retour
    </a>

    <h2 class="text-center">📩 Messages reçus</h2>

    <?php if (mysqli_num_rows($messages_result) > 0) : ?>
        <div class="row">
            <?php while ($message = mysqli_fetch_assoc($messages_result)) : ?>
                <div class="col-md-6">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <strong><?= htmlspecialchars($message['title']) ?></strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Formateur :</strong> <?= htmlspecialchars($message['trainer_first_name']) . " " . htmlspecialchars($message['trainer_last_name']) ?></p>
                            <p><strong>Message :</strong> <?= nl2br(htmlspecialchars($message['message'])) ?></p>
                            <?php if (!empty($message['response'])) : ?>
                                <div class="alert alert-success mt-2">
                                    <p><strong>Réponse du formateur :</strong> <?= nl2br(htmlspecialchars($message['response'])) ?></p>
                                </div>
                            <?php else : ?>
                                <div class="alert alert-warning mt-2">Le formateur n'a pas encore répondu.</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer text-muted text-end">
                            Envoyé le <?= date('d/m/Y à H:i', strtotime($message['created_at'])) ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <div class="alert alert-info text-center">Aucun message reçu.</div>
    <?php endif; ?>

</div>

</body>
</html>