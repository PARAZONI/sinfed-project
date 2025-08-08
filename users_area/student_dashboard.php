<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un élève
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

echo "Bienvenue, " . $_SESSION['user_name'] . "! Vous êtes connecté en tant qu'élève.";
header("Location: ../users_area/student_home.php");
exit;
?>