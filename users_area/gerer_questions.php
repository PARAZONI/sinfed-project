<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté et est un formateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'formateur') {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
include('../config/db.php');

// Requête pour récupérer toutes les questions posées par les élèves
$query = "SELECT id, question, created_at FROM discussions";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Gestion des questions</h1>
        <p>Vous pouvez répondre aux questions posées par les élèves.</p>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Question</th>
                        <th>Date de création</th>
                        <th>Répondre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['question']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td><a href="repondre.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Répondre</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune question posée pour le moment.</p>
        <?php endif; ?>

        <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
        <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Fermer la connexion
mysqli_close($conn);
?>