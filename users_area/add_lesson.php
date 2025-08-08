<?php
include('../config/db.php');
session_start();

// Vérifier si le formateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$trainer_id = $_SESSION['user_id']; // Récupérer l'ID du formateur connecté

// Récupérer les cours créés par le formateur
$query_courses = "SELECT course_id, title FROM courses WHERE trainer_id = ?";
$stmt_courses = mysqli_prepare($conn, $query_courses);
mysqli_stmt_bind_param($stmt_courses, "i", $trainer_id);
mysqli_stmt_execute($stmt_courses);
$result_courses = mysqli_stmt_get_result($stmt_courses);

// Ajouter une leçon
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Gestion du fichier téléchargé (PDF, image, Word)
    $file_path = ''; // Initialisation du chemin du fichier
    if (isset($_FILES['lesson_file']) && $_FILES['lesson_file']['error'] == 0) {
        $file_name = basename($_FILES['lesson_file']['name']);
        $file_tmp = $_FILES['lesson_file']['tmp_name'];
        $upload_dir = '../users_area/uploads/lessons/'; // Répertoire cible pour les fichiers

        // Vérifier et créer le dossier si nécessaire
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Définir le chemin complet du fichier
        $file_path = $upload_dir . $file_name;

        // Déplacer le fichier vers le dossier cible
        if (!move_uploaded_file($file_tmp, $file_path)) {
            $file_path = ''; // Réinitialiser si une erreur se produit
        }
    }

    // Vérifier que tous les champs sont remplis
    if (!empty($course_id) && !empty($title) && !empty($content)) {
        $query = "INSERT INTO lessons (course_id, title, content, file_path, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "isss", $course_id, $title, $content, $file_path);

        if (mysqli_stmt_execute($stmt)) {
            // Notification de succès
            $_SESSION['message'] = "Leçon ajoutée avec succès.";
            // Ajouter la notification dans la table notifications
            $query_notification = "INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())";
            $stmt_notification = mysqli_prepare($conn, $query_notification);
            $message = "Vous avez ajouté une nouvelle leçon : '$title'.";
            mysqli_stmt_bind_param($stmt_notification, "is", $trainer_id, $message);
            mysqli_stmt_execute($stmt_notification);
            mysqli_stmt_close($stmt_notification);

            header("Location: trainer_home.php"); // Redirection après succès
            exit();
        } else {
            // Notification d'erreur
            $_SESSION['message'] = "Erreur lors de l'ajout de la leçon.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = "Veuillez remplir tous les champs.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Leçon</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745; /* Vert pour "Ajouter" */
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #218838; /* Couleur plus foncée au survol */
        }
        .notification {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        a.btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        a.btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<a href="trainer_home" class="btn">
    <i class="fas fa-arrow-left"></i> Retour
</a>

<h1>Ajouter une Nouvelle Leçon</h1>

<!-- Affichage des messages de session -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="notification <?php echo isset($_SESSION['message']) && strpos($_SESSION['message'], 'Erreur') !== false ? 'error' : ''; ?>">
        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data">
    <label for="course_id">Cours associé</label>
    <select id="course_id" name="course_id" required>
        <option value="">Sélectionnez un cours</option>
        <?php while ($row = mysqli_fetch_assoc($result_courses)): ?>
            <option value="<?php echo $row['course_id']; ?>">
                <?php echo htmlspecialchars($row['title']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label for="title">Titre de la Leçon</label>
    <input type="text" id="title" name="title" required>

    <label for="content">Contenu de la Leçon</label>
    <textarea id="content" name="content" required></textarea>

    <label for="lesson_file">Fichier additionnel (PDF, Word, ou Image)</label>
    <input type="file" id="lesson_file" name="lesson_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />

    <button type="submit"><i class="fas fa-plus-circle"></i> Ajouter la Leçon</button>
</form>

<?php include '../includes/footer.php'; ?>

</body>
</html>