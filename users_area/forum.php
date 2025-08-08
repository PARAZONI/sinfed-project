<?php
session_start();

include('../config/db.php');

// Récupération des forums depuis la base de données
$query = "SELECT * FROM forums";
$result = mysqli_query($conn, $query);
?>

<ul>
    <?php while ($forum = mysqli_fetch_assoc($result)) : ?>
        <li>
            <a href="../users_area/forum_discussion.php?forum_id=<?= $forum['id'] ?>"><?= htmlspecialchars($forum['title']) ?></a>
        </li>
    <?php endwhile; ?>
</ul>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">


</head>
<body>
    
</body>
</html>