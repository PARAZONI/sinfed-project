<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

$formateur_id = $_SESSION['user_id']; // ID du formateur connecté

// Récupérer les cours du formateur avec trainer_id
$courses_query = "SELECT course_id, title FROM courses WHERE trainer_id = ?";
$courses_stmt = mysqli_prepare($conn, $courses_query);
mysqli_stmt_bind_param($courses_stmt, "s", $formateur_id);
mysqli_stmt_execute($courses_stmt);
$courses_result = mysqli_stmt_get_result($courses_stmt);

// Traitement du formulaire d'ajout d'exercice ou quiz
if (isset($_POST['ajouter'])) {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $course_id = $_POST['course_id'];
    $formateur_id = $_SESSION['user_id'];

    // Gestion du fichier uploadé
    $fichier = null;
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {
        $dossier = '../uploads/';
        $fichier_nom = basename($_FILES['fichier']['name']);
        $fichier_chemin = $dossier . $fichier_nom;

        if (move_uploaded_file($_FILES['fichier']['tmp_name'], $fichier_chemin)) {
            $fichier = $fichier_nom;
        } else {
            $message = "Erreur lors du téléchargement du fichier.";
        }
    }

    // Insertion dans la base de données
    $query = "INSERT INTO exercises (title, description, type, formateur_id, course_id, fichier) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "ssssss", $titre, $description, $type, $formateur_id, $course_id, $fichier);
        if (mysqli_stmt_execute($stmt)) {
            $message = "Exercice ou Quiz ajouté avec succès.";
            $notification = "Vous avez ajouté un nouvel exercice ou quiz intitulé '$titre'.";
            $query_notification = "INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())";
            $stmt_notification = mysqli_prepare($conn, $query_notification);
            mysqli_stmt_bind_param($stmt_notification, "is", $formateur_id, $notification);
            mysqli_stmt_execute($stmt_notification);
            mysqli_stmt_close($stmt_notification);
        } else {
            $message = "Erreur lors de l'ajout de l'exercice ou quiz. Veuillez réessayer.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $message = "Erreur dans la préparation de la requête.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Exercice ou Quiz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
    <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        .input-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .input-container i {
            margin-right: 10px;
            color: #4CAF50;
        }
        input, textarea, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            font-size: 1.2em;
            margin-top: 20px;
            color: green;
        }
        .btn-back {
            background-color: #f1f1f1;
            color: #333;
            border: 1px solid #ccc;
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-back:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>

    <h2>Ajouter un Exercice ou un Quiz</h2>
    <a href="javascript:history.back()" class="btn-back">Retour</a>

    <?php if (isset($message)) : ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="add_exercise.php" method="post" enctype="multipart/form-data">
        <div class="input-container">
            <i class="fas fa-pencil-alt"></i>
            <label for="titre">Titre de l'exercice ou quiz :</label>
            <input type="text" id="titre" name="titre" required>
        </div>

        <div class="input-container">
            <i class="fas fa-align-left"></i>
            <label for="description">Description :</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <div class="input-container">
            <i class="fas fa-cogs"></i>
            <label for="type">Type :</label>
            <select id="type" name="type">
                <option value="exercice">Exercice</option>
                <option value="quiz">Quiz</option>
            </select>
        </div>

        <div class="input-container">
            <i class="fas fa-book"></i>
            <label for="course_id">Sélectionner un Cours :</label>
            <select id="course_id" name="course_id" required>
                <option value="">Sélectionnez un cours</option>
                <?php while ($course = mysqli_fetch_assoc($courses_result)) : ?>
                    <option value="<?php echo $course['course_id']; ?>"><?php echo $course['title']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="input-container">
            <i class="fas fa-upload"></i>
            <label for="fichier">Télécharger un fichier (facultatif) :</label>
            <input type="file" id="fichier" name="fichier">
        </div>

        <button type="submit" name="ajouter">Ajouter</button>
    </form>

    <?php include '../includes/footer.php'; ?>

</body>
</html>