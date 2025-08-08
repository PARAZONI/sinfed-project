<?php
// Connexion à la base de données
include('../config/db.php');

// Vérification de la connexion
if (!$conn) {
    die("Connexion échouée : " . mysqli_connect_error());
}

// Message initial vide
$message = "";

// Si le formulaire de validation est soumis
if (isset($_POST['validate'])) {
    // Récupérer l'ID du formateur à valider
    $trainer_id = isset($_POST['trainer_id']) ? (int)$_POST['trainer_id'] : 0;

    if ($trainer_id > 0) {
        // Requête SQL pour mettre à jour le statut du formateur
        $query = "UPDATE trainers SET status = 'approved' WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            // Lier l'ID du formateur comme entier
            mysqli_stmt_bind_param($stmt, "i", $trainer_id);

            // Exécuter la requête
            if (mysqli_stmt_execute($stmt)) {
                $message = "Formateur validé avec succès.";
            } else {
                $message = "Erreur lors de la validation : " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $message = "Erreur dans la préparation de la requête : " . mysqli_error($conn);
        }
    } else {
        $message = "ID de formateur invalide.";
    }
}

// Récupérer les formateurs avec un statut "pending"
$query = "SELECT * FROM trainers WHERE status = 'pending'";
$result = mysqli_query($conn, $query);

// Vérifier si des formateurs en attente sont trouvés
$trainers = [];
if ($result && mysqli_num_rows($result) > 0) {
    $trainers = mysqli_fetch_all($result, MYSQLI_ASSOC);
} elseif (!$result) {
    $message = "Erreur lors de la récupération des formateurs : " . mysqli_error($conn);
}

// Fermer la connexion
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Formateurs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="assets/img/SINFED_Image.jpeg" rel="icon">
  <link href="assets/img/SINFED_Image.jpeg" rel="SINFED_Image">


</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Gérer les Formateurs</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($trainers)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Description</th>
                        <th>CV</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trainers as $trainer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($trainer['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($trainer['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($trainer['email']); ?></td>
                            <td><?php echo htmlspecialchars(!empty($trainer['description']) ? $trainer['description'] : 'Aucune description'); ?></td>
                            <td>
                                <?php if (!empty($trainer['cv_path'])): ?>
                                    <a href="../<?php echo htmlspecialchars($trainer['cv_path']); ?>" target="_blank">Voir le CV</a>
                                <?php else: ?>
                                    Pas de CV disponible
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="trainer_id" value="<?php echo $trainer['id']; ?>">
                                    <button type="submit" name="validate" class="btn btn-success btn-sm">Valider</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun formateur en attente de validation.</p>
        <?php endif; ?>
    </div>
</body>
</html>