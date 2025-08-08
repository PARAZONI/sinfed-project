<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si le formateur est connecté
$teacher_id = $_SESSION['teacher_id'] ?? null;
if (!$teacher_id) {
    header('Location: ../login.php');
    exit;
}

// Récupérer toutes les catégories
$query = "SELECT * FROM forum_categories";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des catégories du forum</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h1>Gestion des catégories du forum</h1>

    <a href="ajouter_categorie.php">Ajouter une catégorie</a>

    <table border="1">
        <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td>
                    <a href="modifier_categorie.php?category_id=<?php echo $row['category_id']; ?>">Modifier</a> | 
                    <a href="supprimer_categorie.php?category_id=<?php echo $row['category_id']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette catégorie ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="../dashboard.php">Retour au tableau de bord</a>

    <?php include '../includes/footer.php'; ?>
</body>
</html>