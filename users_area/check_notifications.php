<?php
include('../config/db.php');
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$user_id) {
    echo json_encode(["new_notification" => false]);
    exit;
}

// Vérifier s'il y a de nouvelles notifications non lues
$query = "SELECT message FROM notifications WHERE user_id = ? AND role = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 1";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "is", $user_id, $user_role);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode(["new_notification" => true, "message" => $row['message']]);
} else {
    echo json_encode(["new_notification" => false]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

</head>
<body>
    
</body>
</html>