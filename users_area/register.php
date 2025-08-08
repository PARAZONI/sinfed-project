<?php
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // student ou trainer
    $description = $role == 'trainer' ? htmlspecialchars($_POST['description']) : null;
    $cv_path = null; 

    if ($role == 'trainer' && isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $cv_name = time() . '_' . basename($_FILES['cv']['name']);
        $cv_path = 'uploads/cv/' . $cv_name;
        move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path);
    }

    if ($role == 'student') {
        $sql = "INSERT INTO students (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
    } else {
        $sql = "INSERT INTO trainers (first_name, last_name, email, password, description, cv_path, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    }

    $stmt = mysqli_prepare($conn, $sql);
    
    if ($role == 'student') {
        mysqli_stmt_bind_param($stmt, "ssss", $first_name, $last_name, $email, $password);
    } else {
        mysqli_stmt_bind_param($stmt, "ssssss", $first_name, $last_name, $email, $password, $description, $cv_path);
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: login.php");
        exit();
    } else {
        $error_message = "Erreur lors de l'inscription.";
    }

    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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

    <script>
        function toggleTrainerFields() {
            var role = document.getElementById("role").value;
            var trainerFields = document.getElementById("trainerFields");
            trainerFields.style.display = role === "trainer" ? "block" : "none";
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Inscription</h2>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Vous êtes :</label>
                <select name="role" id="role" class="form-control" onchange="toggleTrainerFields()" required>
                    <option value="student">Élève</option>
                    <option value="trainer">Formateur</option>
                </select>
            </div>
            
            <div id="trainerFields" style="display: none;">
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>CV (PDF uniquement)</label>
                    <input type="file" name="cv" class="form-control-file" accept=".pdf">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
            <div class="#">
            <p class="small fw-bold mt-4 mb-5">Vous Avez Déjà Un Compte? 
              <a href="login.php" class="text-danger">Connexion</a></p>
          </div>
        </form>
    </div>
</body>
</html>