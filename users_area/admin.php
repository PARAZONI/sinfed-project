<?php
// Connexion à la base de données
include('../config/db.php');
session_start();

// Vérifier si l'utilisateur est connecté et s'il est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Requête pour récupérer les formateurs en attente de validation
$query = "SELECT id, nom, email, status FROM users WHERE role = 'formateur' AND status = 'en attente'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Administration - Valider les formateurs</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <h1>Administration - Valider les formateurs</h1>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nom']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <a href="valider_formateur.php?id=<?php echo $row['id']; ?>&action=valider" class="btn btn-success">Valider</a>
                            <a href="valider_formateur.php?id=<?php echo $row['id']; ?>&action=refuser" class="btn btn-danger">Refuser</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "<p>Aucun formateur en attente de validation.</p>";
}

mysqli_close($conn);
?>