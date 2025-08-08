<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Initialisation des variables
$id = $titre = $description = $type = ''; // Valeurs par défaut pour éviter les erreurs de variable non définie

// Récupérer l'exercice à modifier si l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Récupère l'ID de l'exercice
    $query = "SELECT * FROM exercises WHERE exercise_id = ? AND formateur_id = ?"; // Utilise exercise_id
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $titre = $row['title']; // Utilise 'title' au lieu de 'titre'
        $description = $row['description'];
        $type = $row['type'];
    } else {
        echo "Exercice non trouvé.";
        exit;
    }
}

// Si le formulaire est soumis, mettre à jour l'exercice
if (isset($_POST['modifier'])) {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $type = $_POST['type'];

    // Mise à jour de l'exercice dans la base de données
    $query = "UPDATE exercises SET title = ?, description = ?, type = ? WHERE exercise_id = ? AND formateur_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $titre, $description, $type, $id, $_SESSION['user_id']);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Exercice modifié avec succès.";
    } else {
        $message = "Erreur lors de la modification de l'exercice.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Exercice</title>
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


</head>
<body>
<?php include '../includes/header.php'; ?>

<h2>Modifier l'Exercice</h2>

<?php if (isset($message)) : ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<form action="edit_exercise.php?id=<?php echo htmlspecialchars($id); ?>" method="post">
    <label for="titre">Titre :</label>
    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($titre); ?>" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea>

    <label for="type">Type :</label>
    <select id="type" name="type">
        <option value="exercice" <?php echo $type == 'exercice' ? 'selected' : ''; ?>>Exercice</option>
        <option value="quiz" <?php echo $type == 'quiz' ? 'selected' : ''; ?>>Quiz</option>
    </select>

    <button type="submit" name="modifier">Modifier</button>
</form>

<?php include '../includes/footer.php'; ?>
</body>
</html>