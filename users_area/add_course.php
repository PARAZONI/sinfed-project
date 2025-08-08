<?php
include('../config/db.php');
session_start();

// Vérifie si le formateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$trainer_id = $_SESSION['user_id'];  // Récupère l'ID du formateur connecté

$message = "";

// Gestion du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer et sécuriser les données
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    // Vérifier que tous les champs sont remplis
    if (empty($title) || empty($description) || empty($start_date) || empty($status)) {
        $message = "<div class='alert alert-danger text-center'>Tous les champs sont requis.</div>";
    } else {
        // Insérer le cours dans la base de données
        $query = "INSERT INTO courses (title, description, start_date, end_date, status, trainer_id) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssi", $title, $description, $start_date, $end_date, $status, $trainer_id);

        if (mysqli_stmt_execute($stmt)) {
            $course_id = mysqli_insert_id($conn);

            // Ajouter une notification pour le formateur
            $trainer_message = "Vous avez ajouté un nouveau cours : $title.";
            $notif_trainer_query = "INSERT INTO notifications (user_id, role, message, is_read, created_at) 
                                    VALUES (?, 'trainer', ?, 0, NOW())";
            $stmt_notif_trainer = mysqli_prepare($conn, $notif_trainer_query);
            mysqli_stmt_bind_param($stmt_notif_trainer, "is", $trainer_id, $trainer_message);

            if (!mysqli_stmt_execute($stmt_notif_trainer)) {
                $message .= "<div class='alert alert-danger text-center'>Erreur lors de l'ajout de la notification pour le formateur : " . mysqli_error($conn) . "</div>";
            }
            mysqli_stmt_close($stmt_notif_trainer);

            // Ajouter une notification pour tous les étudiants
            $students_query = mysqli_query($conn, "SELECT id FROM students");
            if (mysqli_num_rows($students_query) > 0) {
                while ($row = mysqli_fetch_assoc($students_query)) {
                    $student_id = $row['id'];
                    $notif_message = "Un nouveau cours \"$title\" a été ajouté.";

                    $notif_student_query = "INSERT INTO notifications (user_id, role, message, is_read, created_at) 
                                            VALUES (?, 'student', ?, 0, NOW())";
                    $stmt_notif_student = mysqli_prepare($conn, $notif_student_query);
                    mysqli_stmt_bind_param($stmt_notif_student, "is", $student_id, $notif_message);

                    if (!mysqli_stmt_execute($stmt_notif_student)) {
                        $message .= "<div class='alert alert-danger text-center'>Erreur lors de l'ajout de la notification pour l'étudiant $student_id : " . mysqli_error($conn) . "</div>";
                    }
                    mysqli_stmt_close($stmt_notif_student);
                }
            } else {
                $message .= "<div class='alert alert-warning text-center'>Aucun étudiant inscrit pour recevoir la notification.</div>";
            }

            $message .= "<div class='alert alert-success text-center'>Cours ajouté avec succès !</div>";
        } else {
            $message .= "<div class='alert alert-danger text-center'>Erreur lors de l'ajout du cours : " . mysqli_error($conn) . "</div>";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            background-color: #f0f4f8;
            font-family: Arial, sans-serif;
        }

        .containers {
            max-width: 500px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            margin-left: auto;
            margin-right: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        h2 i {
            margin-right: 8px;
        }

        .alert {
            margin-bottom: 20px;
        }

        .btn-custom {
            background-color: #4CAF50;
            color: white;
            border-radius: 25px;
            font-weight: bold;
            padding: 10px 20px;
            width: 100%;
        }

        .btn-custom:hover {
            background-color: #45a049;
        }

        .btn-back {
            background-color: #f44336;
            color: white;
            border-radius: 25px;
            font-weight: bold;
            margin-top: 10px;
        }

        .btn-back:hover {
            background-color: #e53935;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 12px;
            font-size: 14px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.4);
        }

        .mb-3 {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="containers">
    <h2><i class="fas fa-plus-circle"></i> Ajouter un Nouveau Cours</h2>

    <?= $message; ?>

    <a href="../users_area/trainer_home.php" class="btn btn-back mb-4">
        <i class="fas fa-arrow-left"></i> Retour à l'accueil
    </a>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="title" class="form-label">Titre du Cours</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Titre du cours" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description du Cours</label>
            <textarea id="description" name="description" class="form-control" placeholder="Décrivez brièvement le cours" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Date de Début</label>
            <input type="date" id="start_date" name="start_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Date de Fin</label>
            <input type="date" id="end_date" name="end_date" class="form-control">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Statut du Cours</label>
            <select id="status" name="status" class="form-select" required>
                <option value="En cours">En cours</option>
                <option value="Terminé">Terminé</option>
                <option value="À venir">À venir</option>
            </select>
        </div>

        <button type="submit" class="btn btn-custom">
            <i class="fas fa-save"></i> Ajouter le Cours
        </button>
    </form>
</div>

</body>
</html>