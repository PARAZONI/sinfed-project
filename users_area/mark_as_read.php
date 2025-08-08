<?php
include('../config/db.php');
session_start();

// Vérifier si l'utilisateur est connecté
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
    exit;
}

// Vérifier si l'ID de la notification a été fourni
if (isset($_POST['id'])) {
    $notification_id = $_POST['id'];

    // Mettre à jour la notification pour marquer comme lue
    $query = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $notification_id, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de la notification.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de notification manquant.']);
}
?>