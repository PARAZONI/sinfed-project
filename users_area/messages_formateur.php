<?php
include('../Config/db.php');
session_start();

// vérification de l'accès
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'trainer') {
    header("Location: login.php");
    exit();
}

$trainer_id = $_SESSION['user_id']; // id du formateur connecté

// traitement de la réponse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message_id'], $_POST['response'])) {
    $message_id = $_POST['message_id'];
    $response = trim($_POST['response']);

    if (!empty($response)) {
        $update_query = "UPDATE messages SET response = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "si", $response, $message_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// récupération des messages des élèves
$query = "SELECT m.id, m.message, m.response, m.created_at, 
                 s.first_name AS student_first_name, s.last_name AS student_last_name, 
                 c.title AS course_title
          FROM messages m
          JOIN courses c ON m.course_id = c.course_id
          JOIN students s ON m.student_id = s.id
          WHERE c.trainer_id = ? 
          ORDER BY m.created_at DESC";

$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $trainer_id);
    mysqli_stmt_execute($stmt);
    $messages_result = mysqli_stmt_get_result($stmt);
} else {
    die("erreur sql : " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>messages des élèves</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .message-card {
            border-left: 5px solid #007bff;
        }
        .response-box {
            background-color: #e9f5ff;
            border-left: 5px solid #28a745;
        }
        .btn-response {
            background-color: #007bff;
            color: white;
        }
        .btn-response:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
        }
        .btn-back:hover {
            background-color: #495057;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <!-- bouton retour -->
    <a href="javascript:history.back()" class="btn btn-back mb-3"><i class="fas fa-arrow-left"></i> retour</a>

    <h2 class="text-center"><i class="fas fa-envelope-open-text"></i> 📩 messages des élèves</h2>

    <?php if ($messages_result && mysqli_num_rows($messages_result) > 0) : ?>
        <?php while ($message = mysqli_fetch_assoc($messages_result)) : ?>
            <div class="card my-3 message-card shadow">
                <div class="card-header bg-primary text-white">
                    <strong><i class="fas fa-user-graduate"></i> élève :</strong> <?= htmlspecialchars($message['student_first_name']) . " " . htmlspecialchars($message['student_last_name']) ?>
                    <br>
                    <strong><i class="fas fa-book-open"></i> cours :</strong> <?= htmlspecialchars($message['course_title']) ?>
                </div>
                <div class="card-body">
                    <p><strong><i class="fas fa-comment-dots"></i> message :</strong></p>
                    <p class="p-3 bg-light rounded"><?= nl2br(htmlspecialchars($message['message'])) ?></p>

                    <?php if ($message['response']) : ?>
                        <div class="p-3 mt-3 response-box rounded">
                            <strong><i class="fas fa-reply"></i> réponse :</strong>
                            <p><?= nl2br(htmlspecialchars($message['response'])) ?></p>
                        </div>
                    <?php else : ?>
                        <form method="POST">
                            <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                            <textarea name="response" class="form-control mb-2" placeholder="répondre..." required></textarea>
                            <button type="submit" class="btn btn-response w-100"><i class="fas fa-paper-plane"></i> envoyer</button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="card-footer text-muted text-end">
                    <i class="fas fa-clock"></i> envoyé le <?= date('d/m/Y à H:i', strtotime($message['created_at'])) ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> aucun message trouvé.</div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
