<?php
include('../config/db.php');
session_start();

// Vérifier si l'utilisateur est connecté
$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$user_id) {
    header('Location: ../login.php');
    exit;
}

// Récupérer uniquement les notifications du formateur (trainer)
$query = "SELECT * FROM notifications WHERE user_id = ? AND role = 'trainer' ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id); // Seul l'ID de l'utilisateur est nécessaire ici
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Marquer les notifications comme lues
$update_query = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND role = 'trainer'";
$stmt_update = mysqli_prepare($conn, $update_query);
mysqli_stmt_bind_param($stmt_update, "i", $user_id);
mysqli_stmt_execute($stmt_update);

// Requête pour récupérer les notifications de l'utilisateur connecté
$query_notifications = "SELECT id, message, is_read, created_at FROM notifications WHERE user_id = ? AND role = 'trainer' ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $query_notifications);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$notifications_result = mysqli_stmt_get_result($stmt);

// Vérifier si la requête a réussi
if (!$notifications_result) {
    die("Erreur lors de la récupération des notifications : " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
            <!-- Favicons -->
            <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .containers {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4CAF50;
            text-align: center;
        }
        .notification {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .notification.read {
            background-color: #e0e0e0;
            color: #777;
        }
        .notification .message {
            font-size: 16px;
        }
        .notification .date {
            font-size: 12px;
            color: #888;
        }
        .btn-mark-read {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn-mark-read:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<?php include('../includes/header_trainer.php'); ?>

<div class="containers">
    <h2>Vos notifications</h2>

    <?php
    if (mysqli_num_rows($notifications_result) > 0) {
        while ($notification = mysqli_fetch_assoc($notifications_result)) {
            $read_class = $notification['is_read'] ? 'read' : '';
            echo '<div class="notification ' . $read_class . '" id="notif_' . $notification['id'] . '">';
            echo '<span class="message">' . htmlspecialchars($notification['message']) . '</span><br>';
            echo '<span class="date">Reçu le: ' . $notification['created_at'] . '</span><br>';
            if (!$notification['is_read']) {
                echo '<button class="btn-mark-read" onclick="markAsRead(' . $notification['id'] . ')">Marquer comme lu</button>';
            }
            echo '</div>';
        }
    } else {
        echo "<p>Aucune notification pour le moment.</p>";
    }
    ?>
</div>

<script>
    // Fonction pour marquer une notification comme lue
    function markAsRead(notificationId) {
        // Mettre à jour la notification comme lue via AJAX
        fetch('mark_as_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + notificationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Masquer la notification une fois qu'elle est lue
                document.getElementById('notif_' + notificationId).classList.add('read');
                // Cacher le bouton "Marquer comme lu"
                document.querySelector('#notif_' + notificationId + ' .btn-mark-read').style.display = 'none';
            } else {
                alert('Erreur lors de la mise à jour de la notification.');
            }
        });
    }

    // Vérifier les nouvelles notifications toutes les 30 secondes
    setInterval(() => {
        fetch('check_notifications.php')
            .then(response => response.json())
            .then(data => {
                if (data.new_notification) {
                    showNotification(data.message);
                }
            });
    }, 30000);
</script>

</body>
</html>