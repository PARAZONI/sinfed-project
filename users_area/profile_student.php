<?php
session_start();
include('../config/db.php');

// vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// définir la langue par défaut
if (!isset($_SESSION['langue'])) {
    $_SESSION['langue'] = 'fr';
}


$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// récupérer les informations de l'utilisateur
if ($user_role == 'student') {
    $sql = "SELECT * FROM students WHERE id = ?";
} else {
    $sql = "SELECT * FROM trainers WHERE id = ?";
}

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// mise à jour du profil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);

    // gestion de la photo de profil
    if (!empty($_FILES['profile_photo']['name'])) {
        $profile_photo = basename($_FILES['profile_photo']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $profile_photo;
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_file);
    } else {
        $profile_photo = $user['profile_photo'];
    }

    if ($user_role == 'student') {
        $sql = "UPDATE students SET first_name = ?, last_name = ?, email = ?, profile_photo = ? WHERE id = ?";
    } else {
        $sql = "UPDATE trainers SET first_name = ?, last_name = ?, email = ?, profile_photo = ? WHERE id = ?";
    }

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $last_name, $email, $profile_photo, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: profile_student.php?success=1");
    exit();
}

// changement de mot de passe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // vérifier si l'ancien mot de passe est correct
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            if ($user_role == 'student') {
                $sql = "UPDATE students SET password = ? WHERE id = ?";
            } else {
                $sql = "UPDATE trainers SET password = ? WHERE id = ?";
            }

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header("Location: profile_student.php?success=2");
            exit();
        } else {
            $error = "Les mots de passe ne correspondent pas.";
        }
    } else {
        $error = "Mot de passe actuel incorrect.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Amatic+SC:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
            background-image: url('../assets/image_profile_élève.jpeg');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
        }
        .containers {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
        }
        .profile-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .profile-photo {
            border-radius: 50%;
            max-width: 150px;
            border: 3px solid #fff;
        }
        .info-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<?php include('../includes/header_student.php'); ?>

<div class="containers mt-5">
    <div class="profile-header">
        <h2>Mon Profil</h2>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
        <div class="alert alert-success text-center">Profil mis à jour avec succès.</div>
    <?php elseif (isset($_GET['success']) && $_GET['success'] == 2) : ?>
        <div class="alert alert-success text-center">Mot de passe changé avec succès.</div>
    <?php elseif (isset($error)) : ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row mt-4">
        <div class="col-md-4 text-center">
            <img src="<?php echo $user['profile_photo'] ? '../uploads/' . $user['profile_photo'] : 'https://via.placeholder.com/150'; ?>" 
                 alt="Photo de profil" class="profile-photo img-fluid">
        </div>

        <div class="col-md-8">
            <div class="info-card">
                <h3>Bienvenue, <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?> !</h3>
                <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <?php if ($user_role == 'student') : ?>
                    <p><strong>Rôle :</strong> Élève</p>
                <?php else : ?>
                    <p><strong>Rôle :</strong> Formateur</p>
                    <p><strong>Description :</strong> <?php echo htmlspecialchars($user['description']); ?></p>
                    <p><strong>Statut :</strong> <?php echo ucfirst($user['status']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <hr>
    
    <h4>Modifier mon profil</h4>
    <form action="profile_student.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="update_profile">
        <div class="form-group">
            <label>Prénom :</label>
            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
        </div>
        <div class="form-group">
            <label>Nom :</label>
            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>
        <div class="form-group">
            <label>Email :</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label>Photo de profil :</label>
            <input type="file" class="form-control" name="profile_photo">
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>


<?php include('../includes/footer.php'); ?>

</body>
</html>