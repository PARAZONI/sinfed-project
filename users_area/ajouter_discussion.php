<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// vérifier si l'utilisateur est connecté
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location: ../login.php');
    exit();
}

// vérifier si une catégorie est sélectionnée
if (!isset($_GET['category_id'])) {
    die("catégorie non spécifiée.");
}

$category_id = intval($_GET['category_id']);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $query = "insert into forum_discussions (category_id, title, content, user_id) values (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "issi", $category_id, $title, $content, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            header("location: discussions.php?category_id=" . urlencode($category_id));
            exit();
        } else {
            $message = "erreur lors de l'ajout de la discussion.";
        }
    } else {
        $message = "tous les champs sont requis.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nouvelle discussion</title>
    <link rel="stylesheet" href="../css/style.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h1>créer une nouvelle discussion</h1>

    <?php if ($message) : ?>
        <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="ajouter_discussion.php?category_id=<?php echo htmlspecialchars($category_id); ?>" method="post">
        <label for="title">titre :</label>
        <input type="text" name="title" id="title" required>

        <label for="content">message :</label>
        <textarea name="content" id="content" required></textarea>

        <button type="submit">publier</button>
    </form>

    <a href="discussions.php?category_id=<?php echo htmlspecialchars($category_id); ?>">retour</a>

    <?php include '../includes/footer.php'; ?>
</body>
</html>