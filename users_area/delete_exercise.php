<?php
include('../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = "";

// DEBUG : Afficher l'URL actuelle et les paramètres GET
echo "<pre>URL : " . $_SERVER['REQUEST_URI'] . "</pre>";
echo "<pre>GET : "; var_dump($_GET); echo "</pre>";

// Vérifier si l'ID est bien présent dans l'URL
if (!isset($_GET['delete_id']) || empty($_GET['delete_id'])) {    $message = "ID de l'exercice non spécifié.";
} else {
    $id = intval($_GET['delete_id']); // Sécurisation de l'ID
    $formateur_id = $_SESSION['user_id'];

    // Vérifier si l'exercice existe bien
    $check_query = "SELECT * FROM exercises WHERE exercise_id = ? AND formateur_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "is", $id, $formateur_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) === 0) {
        $message = "Aucun exercice trouvé à supprimer.";
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_delete"])) {
        // Exécuter la suppression uniquement si le formulaire est soumis
        $delete_query = "DELETE FROM exercises WHERE exercise_id = ? AND formateur_id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "is", $id, $formateur_id);

        if (mysqli_stmt_execute($delete_stmt)) {
            header('Location: view_exercise.php?message=Exercice supprimé avec succès');
            exit;
        } else {
            $message = "Erreur lors de la suppression : " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Exercice</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h2>Suppression de l'Exercice</h2>

    <?php if (!empty($message)) : ?>
        <div class="message" style="color: red;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($id) && empty($message)) : ?>
        <p>Êtes-vous sûr de vouloir supprimer cet exercice ?</p>
        <form action="delete_exercise.php?id=<?php echo $id; ?>" method="post">
            <button type="submit" name="confirm_delete">Oui, supprimer</button>
            <a href="view_exercise.php">Annuler</a>
        </form>
    <?php endif; ?>

    <?php include '../includes/footer.php'; ?>
</body>
</html>