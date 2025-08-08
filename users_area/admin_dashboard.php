<?php
include('../config/db.php');
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Requête pour récupérer tous les formateurs en attente
$query = "SELECT * FROM users WHERE role = 'trainer' AND status = 'pending'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Erreur lors de la récupération des formateurs : " . mysqli_error($conn);
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur</title>
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .approve-btn {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .approve-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h1>Tableau de Bord Administrateur</h1>

<h2>Formateurs en attente d'approbation</h2>

<table>
    <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td>
            <form action="approve_trainer.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="approve-btn">Approuver</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>

</table>

</body>
</html>

<?php
// Fermer la connexion
mysqli_close($conn);
?>