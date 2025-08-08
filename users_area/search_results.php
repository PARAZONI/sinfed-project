<?php
// Inclure la configuration de la base de données
include('../config/db.php');

// Vérifier si l'utilisateur est connecté et récupérer son rôle depuis la session
session_start();
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : ''; // Assure-toi que le rôle de l'utilisateur est bien stocké dans la session

// Récupérer la recherche depuis l'URL
$query = isset($_GET['query']) ? trim($_GET['query']) : ''; // Trim pour enlever les espaces superflus
$query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8'); // Sécurisation de la recherche

// Vérification si le terme de recherche est vide
if (empty($query)) {
    echo"<p text-center text-danger>Aucun résultat trouvé pour '<strong><?php echo htmlspecialchars($query); ?></strong>'.</p>
        <p>Essayez d'affiner votre recherche avec un autre mot-clé.</p>
    <?"; // Redirige vers la page de recherche sans terme
    exit();
}

// Définir les paramètres de pagination
$results_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

// Requête SQL avec recherche et pagination
$sql = "SELECT * FROM courses WHERE title LIKE ? LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $sql);

// Vérifier si la préparation de la requête a réussi
if ($stmt === false) {
    die("Erreur de préparation de la requête : " . mysqli_error($conn));
}

// Lier les paramètres de la requête
$search_query = "%" . $query . "%";
mysqli_stmt_bind_param($stmt, "sii", $search_query, $results_per_page, $offset);

// Exécuter la requête
mysqli_stmt_execute($stmt);

// Récupérer les résultats
$result = mysqli_stmt_get_result($stmt);

// Initialiser un tableau pour stocker les résultats
$results = [];
while ($row = mysqli_fetch_assoc($result)) {
    $results[] = $row;
}

// Nombre total de résultats pour calculer la pagination
$sql_total = "SELECT COUNT(*) FROM courses WHERE title LIKE ?";
$stmt_total = mysqli_prepare($conn, $sql_total);
mysqli_stmt_bind_param($stmt_total, "s", $search_query);
mysqli_stmt_execute($stmt_total);
mysqli_stmt_bind_result($stmt_total, $total_results);
mysqli_stmt_fetch($stmt_total);

$total_pages = ceil($total_results / $results_per_page);

// Fermer la requête et la connexion
mysqli_stmt_close($stmt);
mysqli_stmt_close($stmt_total);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche</title>
    <link rel="stylesheet" href="path/to/your/css/style.css"> <!-- Ajouter ton fichier CSS ici -->
            <!-- Favicons -->
            <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

</head>
<style> 
/* Style général */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

/* Conteneur de résultats */
.search-results {
    width: 80%;
    margin: 20px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Titre de la recherche */
.search-results h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
}

/* Liste des résultats */
.list-group {
    margin-top: 20px;
}

.list-group-item {
    background-color: #f7f7f7;
    border: 1px solid #ddd;
    margin-bottom: 10px;
    padding: 15px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.list-group-item:hover {
    background-color: #e9e9e9;
}

.list-group-item h5 {
    font-size: 18px;
    color: #333;
    margin-bottom: 10px;
}

.list-group-item p {
    color: #555;
    margin-bottom: 15px;
}

/* Bouton "Voir plus" */
.btn {
    background-color: #007bff;
    color: white;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
}

.btn:hover {
    background-color: #0056b3;
}

.btn-sm {
    font-size: 12px;
}

/* Pagination */
.pagination {
    text-align: center;
    margin-top: 20px;
}

.pagination a {
    color: #007bff;
    padding: 8px 12px;
    text-decoration: none;
    margin: 0 5px;
    border-radius: 5px;
}

.pagination a.active {
    background-color: #007bff;
    color: white;
}

.pagination a:hover {
    background-color: #0056b3;
    color: white;
}

/* Message d'absence de résultats */
.no-results {
    font-size: 18px;
    color: #777;
    margin-top: 30px;
    text-align: center;
}



/* Style global */
html, body {
    height: 100%;
    margin: 0;
}

/* Conteneur principal de la page */
body {
    display: flex;
    flex-direction: column;
}

/* Le contenu de la page */
.search-results {
    flex: 1; /* Cela permet au contenu de se remplir de l'espace restant */
}

/* Footer */
footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px 0;
    position: relative;
    width: 100%;
}

</style>
<body>

<?php
// Inclure l'en-tête en fonction du rôle de l'utilisateur
if ($user_role == 'trainer') {
    include '../includes/header_trainer.php';  // En-tête pour le formateur
} elseif ($user_role == 'student') {
    include '../includes/header_student.php';  // En-tête pour l'étudiant
} else {
    // Si le rôle est inconnu ou l'utilisateur n'est pas connecté, tu peux choisir d'afficher un en-tête générique
    include '../includes/header_student.php';  // En-tête pour un utilisateur non connecté
}
?>

<div class="search-results">
    <h2>Résultats de la recherche pour "<strong><?php echo htmlspecialchars($query); ?></strong>"</h2>

    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $result): ?>
                <div class="list-group-item">
                    <h5 class="mb-1"><?php echo htmlspecialchars($result['title']); ?></h5>
                    <p class="mb-1"><?php echo htmlspecialchars($result['description']); ?></p>
                    
                    <!-- Bouton "Voir plus" selon le rôle -->
                    <?php if ($user_role == 'trainer'): ?>
                        <a href="../users_area/mes_cours_trainers.php?id=<?php echo $result['course_id']; ?>" class="btn btn-primary btn-sm">Voir plus</a>
                    <?php elseif ($user_role == 'student'): ?>
                        <a href="../users_area/mes_cours.php?id=<?php echo $result['course_id']; ?>" class="btn btn-primary btn-sm">Voir plus</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary btn-sm">Voir plus</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php
                // Affichage des liens de pagination autour de la page actuelle
                $pagination_range = 5; // Nombre de pages autour de la page actuelle
                $start = max(1, $page - floor($pagination_range / 2));
                $end = min($total_pages, $page + floor($pagination_range / 2));
            ?>

            <?php if ($page > 1): ?>
                <a href="search_results.php?query=<?php echo urlencode($query); ?>&page=<?php echo $page - 1; ?>">Précédent</a>
            <?php endif; ?>

            <?php for ($i = $start; $i <= $end; $i++): ?>
                <a href="search_results.php?query=<?php echo urlencode($query); ?>&page=<?php echo $i; ?>" 
                   class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="search_results.php?query=<?php echo urlencode($query); ?>&page=<?php echo $page + 1; ?>">Suivant</a>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <p text-center text-danger>Aucun résultat trouvé pour "<strong><?php echo htmlspecialchars($query); ?></strong>".</p>
        <p>Essayez d'affiner votre recherche avec un autre mot-clé.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>