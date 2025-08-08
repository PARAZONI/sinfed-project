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

// charger le fichier de langue correspondant
$lang_file = "languages/$lang.php";
if (file_exists($lang_file)) {
    include($lang_file);
} else {
    include("languages/fr.php");
}
?>