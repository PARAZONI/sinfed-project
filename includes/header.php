<?php
// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure la base de données si nécessaire
include('../config/db.php');?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SINFED - Formation en Cybersécurité</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

  <!-- En-tête -->
  <header class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
    <a href="../index1.php" class="text-white h2">
    <img src="../assets/SINFED_Image.jpeg" alt="SINFED" class="logo">
</a>

<style>
    .logo {
        height: 100px;
        border-radius: 380px;
        transition: transform 0.5s ease-in-out;
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
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      
      
      
      <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <!-- Menu déroulant pour "À propos" -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              À propos
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#about">Notre Mission</a></li>
              <li><a class="dropdown-item" href="#about">Nos Objectif</a></li>
            </ul>
          </li>
          <!-- Menu déroulant pour "Services" -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownServices" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Services
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownServices">
              <li><a class="dropdown-item" href="#services">Formation Cybersécurité</a></li>
              <li><a class="dropdown-item" href="#services">Consulting en Sécurité</a></li>
            </ul>
          </li>

          <!-- Vérification de la session pour afficher les options de connexion ou de bienvenue -->
          <?php if (isset($_SESSION['user_email'])): ?>
            <!-- Si l'utilisateur est connecté, affiche le message de bienvenue et un bouton pour se déconnecter -->

            <?php else: ?>
            <!-- Si l'utilisateur n'est pas connecté, affiche les liens de connexion et d'inscription -->
            <li class="nav-item">
              <a href="users_area/auth.php" class="nav-link">Connexion</a>
            </li>
            <a href="users_area/logout.php" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">Déconnexion</a>
            <li class="nav-item">
              <a href="users_area/register.php" class="nav-link">S'inscrire</a>
            </li>
          <?php endif; ?>

          <!-- Menu déroulant pour "Témoignages" -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTestimonials" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Témoignages
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownTestimonials">
              <li><a class="dropdown-item" href="#testimonials">Avis des étudiants</a></li>
            </ul>
          </li>
          <li class="nav-item">
              <a href="users_area/profiles.php" class="nav-link">Profile</a>
            </li>


            <?php
// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure la base de données si nécessaire
include('../config/db.php');?>

            <div class="user-info">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-details">
                        <p>
                            Bonjour, 
                            <?php echo htmlspecialchars($_SESSION['user_name']); ?> 
                            <!-- (<?php echo htmlspecialchars($_SESSION['role'] === 'student' ? '#' : '#'); ?>) -->
                        </p>
                        <!-- <?php if (!empty($_SESSION['profile_photo'])): ?>
                            <img src="../SINFED/uploads/2.jpg echo htmlspecialchars($_SESSION['profile_photo']); ?>" alt="Photo de profil" width="50" height="50">
                        <?php else: ?>
                            <img src="../SINFED/uploads/" alt="Photo de profil par défaut" width="50" height="50">
                        <?php endif; ?> -->
                    </div>
                <?php else: ?>
                    <div class="login-link">
                        <a href="../users_area/login.php">Se connecter</a>
                    </div>
                <?php endif; ?>
            </div>




          <!-- Menu déroulant pour "Contact" -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownContact" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Contact
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownContact">
              <li><a class="dropdown-item" href="#contact">Formulaire de Contact</a></li>
            </ul>
          </li>
          <!-- Barre de recherche -->
          <li class="nav-item">
          <form class="d-flex" action="../SINFED/users_area/search_results.php" method="GET">
  <input class="form-control me-2" type="search" name="query" placeholder="Recherche" aria-label="Search">
  <button class="btn btn-custom" type="submit">Rechercher</button>
</form>          </li>
        </ul>
      </div>
    </div>
  </header>

  <!-- Ajout du script Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>