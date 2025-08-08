<?php
session_start();
include('../config/db.php');

// vérifier si l'utilisateur est connecté et si une langue est sélectionnée
if (!isset($_SESSION['user_id']) || !isset($_POST['langue'])) {
    die("Utilisateur non identifié ou langue non définie");
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
$langue = $_POST['langue'];

// mise à jour de la langue dans la base de données
if ($user_role == 'student') {
    $sql = "UPDATE students SET langue = ? WHERE id = ?";
} else {
    $sql = "UPDATE trainers SET langue = ? WHERE id = ?";
}

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $langue, $user_id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['langue'] = $langue; // mettre à jour la session
    session_write_close(); // enregistrer la session avant la redirection
    header("Location: profile_student.php?success=3");
    exit();
} else {
    echo "Erreur lors de la mise à jour.";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>