<?php
include('../Config/db.php');
session_start();

// Vérification de l'accès
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../login.php');
    exit;
}

$student_id = $_SESSION['user_id']; // Récupérer l'ID de l'élève connecté

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insérer le message dans la table messages
    $query = "INSERT INTO messages (course_id, student_id, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iis", $course_id, $student_id, $message);

    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Message envoyé avec succès.";
    } else {
        $error_message = "Erreur lors de l'envoi du message : " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

// Récupérer les cours associés à l'élève dans la nouvelle table 'students_courses'
$query_courses = "SELECT c.course_id, c.title 
                  FROM courses c
                  JOIN student_courses sc ON c.course_id = sc.course_id
                  WHERE sc.student_id = ?";
$stmt_courses = mysqli_prepare($conn, $query_courses);
mysqli_stmt_bind_param($stmt_courses, "i", $student_id);
mysqli_stmt_execute($stmt_courses);
$courses_result = mysqli_stmt_get_result($stmt_courses);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoyer un message</title>
    <link rel="stylesheet" href="../css/style.css">
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
            font-family: Arial, sans-serif;
            background: url('../assets1/image_messages.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding-top: 20px;
            position: relative; /* Assurer que le contenu reste au-dessus de l'image de fond */
            z-index: 2;
        }

        h2 {
            color: #FF5722;
            text-align: center;
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
         /* Ajout d'une légère transparence */

        form {
            background-color: rgba(255, 255, 255, 0.8); 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #FF5722;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #e64a19;
        }

        .icon {
            margin-right: 8px;
        }

        .btn-info {
            background-color: #FF5722;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-info:hover {
            background-color: #e64a19;
        }

        /* Style pour le message de succès */
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        /* Animation de rotation */
        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .icon-rotate {
            animation: rotate 2s linear infinite;
        }

        /* Arrêter l'animation au survol */
        .btn-info:hover .icon-rotate {
            animation: none;
        }
    </style>
</head>
<body>

<?php include '../includes/header_student.php'; ?>

<div class="container">
    <a href="../users_area/student_home.php" class="btn">
        <i class="fas fa-arrow-left"></i> Retour
    </a>

    <h2>Envoyer un message</h2>

    <!-- Afficher le message de succès si disponible -->
    <?php if (isset($success_message)): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="send_message.php">
        <div>
            <label for="course_id"><i class="fas fa-book icon"></i> Cours :</label>
            <select name="course_id" id="course_id" required>
                <option value="">-- Sélectionner un cours --</option>
                <?php
                while ($course = mysqli_fetch_assoc($courses_result)) {
                    echo "<option value='" . htmlspecialchars($course['course_id']) . "'>" . htmlspecialchars($course['title']) . "</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label for="message"><i class="fas fa-comment icon"></i> Message :</label>
            <textarea name="message" id="message" rows="5" required></textarea>
        </div>
        <div>
            <button type="submit"><i class="fas fa-paper-plane"></i> Envoyer</button>
        </div>
    </form>

    <!-- Bouton "Lire les messages" avec animation -->
    <div class="btn-info mb-2">
        <i class="fas fa-eye icon-rotate"></i> <a href="../users_area/messages.php" style="color: white; text-decoration: none;">Lire les messages</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>

<?php
mysqli_stmt_close($stmt_courses);
mysqli_close($conn);
?>