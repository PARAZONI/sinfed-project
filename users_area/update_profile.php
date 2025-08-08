<?php
// Inclusion du fichier de connexion à la base de données
include_once('../config/db.php'); // Assurez-vous que la connexion à la base de données est correcte


// Vérification de la session et récupération de l'ID de l'utilisateur
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour accéder à cette page.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer le rôle de l'utilisateur (par exemple, stocké dans la session ou la base de données)
$role = $_SESSION['role'];  // Cela dépend de la façon dont tu stockes le rôle (student ou trainer)

// Vérifier si un fichier a été téléchargé pour la photo de profil
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profile_photo']['tmp_name'];
    $fileName = $_FILES['profile_photo']['name'];
    $fileSize = $_FILES['profile_photo']['size'];
    $fileType = $_FILES['profile_photo']['type'];

    // Définir le répertoire de destination pour l'upload
    $uploadDir = '../uploads/';
    $destFilePath = $uploadDir . basename($fileName);

    // Vérifier l'extension du fichier (par exemple, .jpg, .jpeg, .png)
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExtension, $allowedExtensions)) {
        // Déplacer le fichier téléchargé dans le répertoire de destination
        if (move_uploaded_file($fileTmpPath, $destFilePath)) {
            // Si le fichier a été téléchargé avec succès, mettre à jour le chemin dans la base de données
            if ($role === 'student') {
                $updateQuery = "UPDATE students SET profile_photo = ? WHERE id = ?";
            } elseif ($role === 'trainer') {
                $updateQuery = "UPDATE trainers SET profile_photo = ? WHERE id = ?";
            } else {
                echo "Rôle inconnu.";
                exit;
            }

            // Préparer et exécuter la requête SQL pour mettre à jour la photo
            $stmt = mysqli_prepare($conn, $updateQuery);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'si', $fileName, $user_id);
                mysqli_stmt_execute($stmt);
            } else {
                echo "Erreur de mise à jour de la photo.";
            }
        } else {
            echo "Erreur lors du téléchargement de la photo.";
        }
    } else {
        echo "Extension de fichier non autorisée.";
    }
}

// Vérifier et mettre à jour les informations personnelles (prénom, nom, email)
if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];

    // Mise à jour des informations dans la bonne table en fonction du rôle
    if ($role === 'student') {
        $updateQuery = "UPDATE students SET first_name = ?, last_name = ?, email = ? WHERE id = ?";
    } elseif ($role === 'trainer') {
        $updateQuery = "UPDATE trainers SET first_name = ?, last_name = ?, email = ? WHERE id = ?";
    } else {
        echo "Rôle inconnu.";
        exit;
    }

    // Préparer et exécuter la requête SQL pour mettre à jour les informations personnelles
    $stmt = mysqli_prepare($conn, $updateQuery);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssi', $firstName, $lastName, $email, $user_id);
        mysqli_stmt_execute($stmt);
        echo "Profil mis à jour avec succès.";
        header('Location: ../users_area/profiles.php');
    exit();
    } else {
        echo "Erreur de mise à jour des informations.";
    }
}
?>

<!-- Formulaire HTML pour l'upload de la photo et la mise à jour des informations -->
<!-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mise à jour du profil</title>
</head>
<body>
    <h2>Mettre à jour votre profil</h2>
    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <label for="first_name">Prénom :</label>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Nom :</label>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="profile_photo">Photo de profil :</label>
        <input type="file" id="profile_photo" name="profile_photo" accept=".jpg, .jpeg, .png"><br><br>

        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html> -->