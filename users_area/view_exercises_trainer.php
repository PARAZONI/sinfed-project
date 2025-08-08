<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'trainer') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');

// Récupérer tous les exercices et leurs réponses, incluant student_id
$query_exercises = "SELECT se.id, e.title, st.first_name, st.last_name, se.grade, se.status, se.student_id
                    FROM student_exercises se
                    JOIN exercises e ON se.exercise_id = e.exercise_id
                    JOIN students st ON se.student_id = st.id";
$stmt_exercises = mysqli_prepare($conn, $query_exercises);
mysqli_stmt_execute($stmt_exercises);
$exercises_result = mysqli_stmt_get_result($stmt_exercises);

mysqli_stmt_close($stmt_exercises);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercices à corriger</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Lien vers Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Amatic+SC:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .table {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .badge {
            font-size: 0.9rem;
        }

        .table a.btn-warning {
            color: white;
            background-color: #f0ad4e;
            border-color: #f0ad4e;
        }

        .table a.btn-warning:hover {
            background-color: #ec971f;
            border-color: #ec971f;
        }

        .btn-return {
            margin-bottom: 20px;
            color: #ffffff;
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-return:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .text-center h1 {
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        .text-center i {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container mt-4">

    <!-- Bouton de retour -->
    <a href="javascript:history.back()" class="btn-return">Retour</a>

    <h1 class="text-center mb-4"><i class="fas fa-tasks"></i>Exercices à corriger</h1>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Nom de l'élève</th>
                <th>Titre de l'exercice</th>
                <th>Note</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($exercises_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo $row['grade'] ? $row['grade'] : 'Non corrigé'; ?></td>
                    <td>
                        <?php if ($row['status'] === 'pending'): ?>
                            <span class="badge bg-warning text-dark">En attente</span>
                        <?php else: ?>
                            <span class="badge bg-success">Corrigé</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] === 'pending'): ?>
                            <a href="grade_exercise.php?exercise_id=<?php echo $row['id']; ?>&student_id=<?php echo $row['student_id']; ?>" class="btn btn-warning">Corriger</a>
                        <?php else: ?>
                            <span class="badge bg-success">Corrigé</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>