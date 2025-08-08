<?php
// Inclure la connexion à la base de données
include('../config/db.php');

// Requête pour récupérer tous les exercices
$query = "SELECT * FROM exercises";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Exercices</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
        }
        .card {
            border-radius: 10px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .btn-correction {
            width: 100%;
        }
        .back-home {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #007bff;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            transition: 0.3s;
        }
        .back-home:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        .back-home i {
            font-size: 24px;
            margin-right: 8px;
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">📚 Liste des Exercices à Corriger</h1>

    <!-- bouton retour à l'accueil -->
    <a href="../users_area/trainer_home.php" class="back-home">
        <i class="fas fa-home"></i> Retour à l'accueil
    </a>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($exercise = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">📌 <?php echo htmlspecialchars($exercise['title']); ?></h5>
                            <p class="card-text text-muted">
                                <strong>Description :</strong> 
                                <?php echo nl2br(htmlspecialchars(substr($exercise['description'], 0, 100))) . '...'; ?>
                            </p>
                            <a href="../users_area/grade_exercise.php?exercise_id=<?php echo $exercise['exercise_id']; ?>" 
                               class="btn btn-primary btn-correction">✏️ Corriger cet exercice</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            📌 Aucun exercice disponible pour le moment.
        </div>
    <?php endif; ?>

</div>

</body>
</html>