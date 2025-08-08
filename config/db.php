<?php
$servername = "localhost";
$username = "root";
$password = "";  // Si tu n'as pas de mot de passe pour l'utilisateur root
$dbname = "sinfed_academy"; // Remplace par le nom de ta base de données

// Créer la connexion
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Vérifier la connexion
if (!$conn) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}
?>