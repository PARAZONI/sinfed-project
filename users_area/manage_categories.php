<?php
include('../config/db.php');
session_start();

// Vérifier si l'utilisateur est admin ou formateur
if (!isset($_SESSION['trainer_id'])) {
    header("Location: login.php");
    exit();
}

// Ajouter une nouvelle catégorie
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "INSERT INTO discussion_categories (name, description) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $name, $description);
    mysqli_stmt_execute($stmt);

    echo "<p>Catégorie ajoutée avec succès.</p>";
    mysqli_stmt_close($stmt);
}

// Récupérer toutes les catégories
$query = "SELECT * FROM discussion_categories";
$result = mysqli_query($conn, $query);
?>
<h1>Gérer les Catégories</h1>
<form method="POST">
    <input type="text" name="name" placeholder="Nom de la catégorie" required>
    <textarea name="description" placeholder="Description de la catégorie" required></textarea>
    <button type="submit" name="add_category">Ajouter</button>
</form>
<h2>Liste des Catégories</h2>
<ul>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <li>
            <?php echo htmlspecialchars($row['name']); ?> - 
            <?php echo htmlspecialchars($row['description']); ?>
        </li>
    <?php endwhile; ?>
</ul>