<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../login.php');
    exit;
}

// Vérifier si une catégorie est sélectionnée
if (!isset($_GET['category_id'])) {
    echo "Catégorie non spécifiée.";
    exit();
}

$category_id = intval($_GET['category_id']);

// Récupérer les informations de la catégorie
$query_category = "SELECT * FROM forum_categories WHERE category_id = ?";
$stmt_category = mysqli_prepare($conn, $query_category);
mysqli_stmt_bind_param($stmt_category, "i", $category_id);
mysqli_stmt_execute($stmt_category);
$result_category = mysqli_stmt_get_result($stmt_category);

if (mysqli_num_rows($result_category) === 0) {
    echo "Catégorie introuvable.";
    exit();
}

$category = mysqli_fetch_assoc($result_category);

// Récupérer toutes les discussions de cette catégorie
$query_discussions = "SELECT d.*, u.username 
                      FROM forum_discussions d 
                      JOIN users u ON d.user_id = u.user_id
                      WHERE d.category_id = ? 
                      ORDER BY d.created_at DESC";
$stmt_discussions = mysqli_prepare($conn, $query_discussions);
mysqli_stmt_bind_param($stmt_discussions, "i", $category_id);
mysqli_stmt_execute($stmt_discussions);
$result_discussions = mysqli_stmt_get_result($stmt_discussions);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussions - <?php echo htmlspecialchars($category['title']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
            <!-- Favicons -->
            <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h1>Discussions : <?php echo htmlspecialchars($category['title']); ?></h1>
    <p><?php echo htmlspecialchars($category['description']); ?></p>

    <a href="ajouter_discussion.php?category_id=<?php echo $category_id; ?>">Créer une nouvelle discussion</a>

    <table border="1">
        <tr>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Date</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result_discussions)) : ?>
            <tr>
                <td><a href="discussion.php?discussion_id=<?php echo $row['discussion_id']; ?>">
                    <?php echo htmlspecialchars($row['title']); ?>
                </a></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="forum.php">Retour au forum</a>

    <?php include '../includes/footer.php'; ?>
</body>
</html>