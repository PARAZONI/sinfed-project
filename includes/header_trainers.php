<?php
// Déterminer l'heure actuelle pour afficher Bonjour ou Bonsoir
$hour = date('H');
if ($hour < 12) {
    $greeting = 'Bonjour';
} elseif ($hour < 18) {
    $greeting = 'Bon après-midi';
} else {
    $greeting = 'Bonsoir';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <!-- Favicons -->
        <link href="assets/img/SINFED_Image.jpeg" rel="icon">
  <link href="assets/img/SINFED_Image.jpeg" rel="SINFED_Image">

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
    /* Styles personnalisés */
    .navbar-custom {
      background-color: #FF5722; /* Orange */
      height: 111px;
    }
    .navbar-custom .nav-link {
      color: white !important;
    }
    .navbar-custom .nav-link:hover {
      color: #4CAF50 !important; /* Vert au survol */
    }
    .profile-pic {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 10px;
    }
    </style>
</head>
<body>

<header class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a href="../users_area/trainer_home.php" class="text-white h2">
            <img src="../assets1/img/logo/ChatGPT Image 4 juin 2025, 15_55_43.png" alt="SINFED" class="logo">
            <style>
    .logo {
        height: 100px;
        border-radius: 380px;
        transition: transform 0.5s ease-in-out;
        margin-top: -45px;
    }

    .logo:hover {
        animation: rotation 2s linear infinite;
    }

    @keyframes rotation {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>

        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_email'])): ?>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="fa-solid fa-user"></i> 
                            <?php echo $greeting; ?>, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a href="users_area/logout.php" class="btn btn-danger me-5">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="users_area/auth.php" class="nav-link"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a href="users_area/register.php" class="nav-link"><i class="fas fa-user-plus"></i> S'inscrire</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <form class="d-flex" action="../users_area/search_results.php" method="GET">
                        <input class="form-control me-2" type="search" name="query" placeholder="Recherche">
                        <button class="btn btn-custom" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>