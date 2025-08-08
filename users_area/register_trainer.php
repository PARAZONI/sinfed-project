<?php
include('../config/db.php'); // Fichier pour la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashage du mot de passe

    // Insertion dans la table trainers avec approved = 0
    $query = "INSERT INTO trainers (name, email, password, approved) VALUES ('$name', '$email', '$password', 0)";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "Votre inscription est en attente d'approbation par l'administration.";
    } else {
        echo "Erreur lors de l'inscription : " . mysqli_error($conn);
    }
}
?>

<form method="POST" action="">
    <input type="text" name="name" placeholder="Nom complet" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">S'inscrire comme formateur</button>
</form>