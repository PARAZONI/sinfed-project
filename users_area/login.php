<?php
session_start();
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role == 'student') {
        $sql = "SELECT * FROM students WHERE email = ?";
    } else {
        $sql = "SELECT * FROM trainers WHERE email = ?";
    }

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            if ($role == 'trainer' && $row['status'] != 'approved') {
                $error_message = "Votre compte est en attente de validation.";
            } else {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['first_name'];
                $_SESSION['user_role'] = $role;

                if ($role == 'student') {
                    header("Location: student_home.php");
                } else {
                    header("Location: trainer_home.php");
                }
                exit();
            }
        } else {
            $error_message = "Mot de passe incorrect.";
        }
    } else {
        $error_message = "Email non trouvé.";
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
    <title>Connexion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="assets/img/SINFED_Image.jpeg" rel="icon">
  <link href="assets/img/SINFED_Image.jpeg" rel="SINFED_Image">


</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Connexion</h2>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="post">
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
                <select name="role" class="form-control" required>
                    <option value="student">Élève</option>
                    <option value="trainer">Formateur</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            <div class="mt-4 pt-2">
              <p class="small fw-bold mt-2 pt-1 mb-0">Je n'ai pas de compte?
                <a href="register.php" class="text-danger">S'Inscrire</a></p>
            </div>

        </form>
    </div>
</body>
</html>