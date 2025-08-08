<?php
include('../config/db.php');

// Récupérer la date actuelle
$current_date = date('Y-m-d');

// Préparer la requête de mise à jour des statuts des cours
$update_query = "
    UPDATE courses 
    SET status = 
        CASE 
            WHEN start_date <= '$current_date' AND (end_date IS NULL OR end_date >= '$current_date') THEN 'En cours'
            WHEN end_date IS NOT NULL AND end_date < '$current_date' THEN 'Terminé'
            ELSE 'À venir'
        END
";

// Exécuter la requête de mise à jour
if (mysqli_query($conn, $update_query)) {
    echo "Mise à jour des statuts réussie.";
} else {
    echo "Erreur lors de la mise à jour des statuts : " . mysqli_error($conn);
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>