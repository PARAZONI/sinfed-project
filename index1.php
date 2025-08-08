
<?php
include('config/db.php');

session_start();
if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
} else {
    $email = '';
}


// récupérer les formations
$sql = "select * from formations";
$result = $conn->query($sql);
?>


<?php

// Vérifier si l'utilisateur est connecté
// if (!isset($_SESSION['user_id'])) {
//     header("Location: users_area/login.php");
//     exit;
// }
?>


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

  <!-- Template Main CSS File -->
  <link href="../SINFED/assets1/style_index1.css" rel="stylesheet">



  <style>
        /* Styles généraux */
        .hero-section {
            background: url('images/hero-bg.jpg') center/cover no-repeat;
            color: white;
            height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .hero-section h1 {
            font-size: 3rem;
        }
        .bg-primary-custom { background-color: #FF5722; }
        .btn-custom { background-color: #FF5722; color: white; }
        .text-custom-green { color: #4CAF50; }
        .navbar-custom { background-color: #FF5722; }
        .navbar-custom .nav-link { color: white !important; }
        .navbar-custom .nav-link:hover { color: #4CAF50 !important; }

        /* Logo animé */
        .logo {
            height: 80px;
            border-radius: 50%;
            transition: transform 0.5s ease-in-out;
        }
        .logo:hover {
            animation: rotation 2s linear infinite;
        }
        @keyframes rotation {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        

        /* Arrondir l'image */
img.rounded {
  border-radius: 120px; /* Ajuste la valeur pour un arrondi plus ou moins marqué */
}

/* Si tu veux arrondir aussi les images du diaporama */
.carousel-inner img {
  border-radius: 120px;
}

/* Style pour la section des témoignages */
.testimonials {
    padding: 60px 0;
    background-color: #f7f7f7; /* Arrière-plan clair */
}

.testimonial-item {
    text-align: center;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    margin: 0 15px; /* Ajoute un espace entre les éléments */
}

.testimonial-item p {
    font-size: 16px;
    color: #666;
    margin-bottom: 20px;
}

.testimonial-item h3 {
    font-size: 20px;
    color: #333;
    margin-bottom: 5px;
}

.testimonial-item span {
    font-size: 14px;
    color: #777;
}

    </style>

</head>
<body>

  <!-- En-tête -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

    <a href="index1.php" class="text-white h2">
    <img src="../SINFED/assets1/img/logo/SINFED_Image.jpeg" alt="SINFED" class="logo">
</a>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="#hero">Home</a></li>
          <li><a href="#about">À Propos</a></li>
          <li><a href="#formation">Formation</a></li>
          <li><a href="#events">Autre activité</a></li>
          <li><a href="#chefs">Chefs</a></li>
          <li><a href="#gallery">Gallery</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="#hero">S'incrire</a></li>
        </ul>
      </nav><!-- .navbar -->

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
  </header><!-- End Header -->


<!-- Section d'introduction -->
<section id="hero" class="hero d-flex align-items-center section-bg">
    <div class="container">
      <div class="row justify-content-between gy-5">
        <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center align-items-center align-items-lg-start text-center text-lg-start">
        <h1 data-aos="fade-up">Boostez votre avenir avec SINFED Technologies</h1>
        <p data-aos="fade-up" data-aos-delay="100">Formation complète en informatique axée sur la pratique, adaptée à tous les niveaux d'apprentissage.</p>
        
          <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
            <a href="../SINFED/users_area/register.php" class="btn-book-a-table">S'inscrire dès maintenant</a>
            <a href="../SINFED/assets1/video/vidéo_Sinfed 2.mp4" class="glightbox play-btn btn-watch-video d-flex align-items-center" data-type="video">
            <i class="bi bi-play-circle"></i><span>Watch Video</span></a>
          </div>
        </div>
        <!-- <div class="col-lg-5 order-1 order-lg-2 text-center text-lg-start ">
          <img src="../SINFED/assets1/image_fond_3.jpeg" class="img-fluid rounded" alt="Image arrondie" data-aos="zoom-out" data-aos-delay="300">
        </div> -->

<!-- Diaporama -->
<div id="carouselExampleAutoplaying" class="carousel slide col-lg-5 order-1 order-lg-2 text-center text-lg-start " data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active  ">
      <img src="../SINFED/assets1/image_fond_2.jpeg" class="d-block w-100 rounded" alt="Image 1" data-aos="zoom-out" data-aos-delay="300">
    </div>
    <div class="carousel-item  ">
      <img src="../SINFED/assets1/image_fond_3.jpeg" class="d-block w-100 rounded" alt="Image 2" data-aos="zoom-out" data-aos-delay="300">
    </div>
    <div class="carousel-item  ">
      <img src="../SINFED/assets1/image _fond_1.jpeg" class="d-block w-100 rounded" alt="Image 3" data-aos="zoom-out" data-aos-delay="300">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
      </div>
    </div>
  </section><!-- End Hero Section -->





      <!-- ======= About Section ======= -->
      <section id="about" class="about">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>À propos de nous</h2>
          <p>En Savoir Plus <span>Sur Nous</span></p>
        </div>

        <div class="row gy-4">
          <div class="col-lg-7 position-relative about-img" style="background-image: url(../SINFED/assets1/image_fond_4.jpeg) ;" data-aos="fade-up" data-aos-delay="150">
            <div class="call-us position-absolute">
              <h4>Contacter Nous!</h4>
              <p>+225 0142323822
              </p>
            </div>
          </div>
          <div class="col-lg-5 d-flex align-items-end" data-aos="fade-up" data-aos-delay="300">
            <div class="content ps-0 ps-lg-5">
              <p class="fst-italic">
              SINFED Technologies(Société Informatique Éducative et Développement) est une entreprise spécialisée dans la fourniture de formations en informatique pratiques dans les écoles. 
              SINFED Technologies représente une opportunité unique de révolutionner l’enseignement informatique en Afrique. Avec un modèle économique solide et une vision claire.
              </p>
              <ul>
                <li><i class="bi bi-check2-all"></i>SINFED offre une formation en informatique pratique via des ordinateurs portables fournis aux élèves, avec une gestion logistique complète et une plateforme de suivi pédagogique en ligne.

                <li><i class="bi bi-check2-all"></i> L’objectif principal est d’introduire des cours pratiques d’informatique dans les écoles secondaires de Côte d’Ivoire afin de renforcer les compétences technologiques des élèves et de les préparer aux exigences du marché du travail moderne.</li>
                <li><i class="bi bi-check2-all"></i> Mission : Devenir un leader dans l’éducation numérique en Afrique, en initiant un changement durable et en s’étendant progressivement à d’autres pays de la sous-région.</li>
              </ul>
              <p>
               SINFED Technologies transformer l’éducation et dynamiser l’innovation à travers des solutions informatiques concrètes, tout en intégrant des secteurs variés comme l’immobilier, l’élevage, le transport, l’urbanisme et les énergies renouvelables.
              </p>

              <div class="position-relative mt-4">
    <img src="../SINFED/assets1/image_fond_6.jpeg" class="img-fluid" alt="">
    <a href="../SINFED/assets1/video/vidéo_Sinfed 2.mp4" class="glightbox play-btn" data-type="video">
        <i class="fa fa-play-circle"></i> <!-- icône play -->
    </a>
</div>
              
            </div>
          </div>
        </div>

      </div>
    </section><!-- End About Section -->




        <!-- ======= Why Us Section ======= -->
        <section id="why-us" class="why-us section-bg">
      <div class="container" data-aos="fade-up">

        <div class="row gy-4">


        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="why-box">
              <h3>Pourquoi Choisi SINFED TECHNOLOGIES ?</h3>
              <p>
              Une solution clé en main pour les établissements scolaires
              Nous offrons aux écoles un service complet, incluant la fourniture de matériel informatique, la gestion logistique, et une plateforme interactive pour le suivi des élèves. Les établissements bénéficient ainsi d’une mise en place rapide et efficace sans contrainte supplémentaire.              </p>
              <div class="text-center">
                <a href="../SINFED/users_area/register.php" class="more-btn">Rejoignez-Nous <i class="bx bx-chevron-right"></i></a>
              </div>
            </div>
          </div>

          <!-- <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="why-box">
              <h3>Est-ce que SINFED Technologies fournit une aide au placement professionnel après avoir terminer un programme ?</h3>
              <p>
                 Oui, nous proposons des services de soutien de carrière, notamment la création de CV, la préparation aux entretients et la mise en relation avec les employeurs technologiques locaux.
              </p>
              <div class="text-center">
                <a href="#" class="more-btn">Learn More <i class="bx bx-chevron-right"></i></a>
              </div>
            </div>
          </div>End Why Box -->

          <div class="col-lg-8 d-flex align-items-center">
            <div class="row gy-4">

              <div class="col-xl-4" data-aos="fade-up" data-aos-delay="200">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-laptop"></i>
                  <h4>Formation 100% pratique</h4>
                  <p>Les élèves apprennent en manipulant directement des ordinateurs portables fournis par SINFED.</p>
                  </div>

                
              </div><!-- End Icon Box -->

              <div class="col-xl-4" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                <i class="bi bi-people"></i>
                <h4>Suivi personnalisé</h4>
                <p>Grâce à notre plateforme, chaque élève bénéficie d'un encadrement adapté à son niveau.</p>
                </div>
              </div><!-- End Icon Box -->

              <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                <i class="bi bi-lightbulb"></i>
                <h4>Approche innovante</h4>
                <p>Nous intégrons des outils numériques et pédagogiques modernes pour un apprentissage optimal.</p>
                </div>
              </div><!-- End Icon Box -->


              <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                <i class="bi bi-graph-up"></i>
                <h4>Impact sur l'employabilité</h4>
                <p>Nos formations permettent aux élèves d'acquérir des compétences recherchées sur le marché du travail.</p>
                </div>
              </div><!-- End Icon Box -->

            </div>
          </div>

        </div>

      </div>
    </section><!-- End Why Us Section -->


    <!-- ======= Stats Counter Section ======= -->
    <section id="stats-counter" class="stats-counter">
      <div class="container" data-aos="zoom-out">

        <div class="row gy-4">

          <div class="">
            <div class="stats-item text-center w-100 h-100">
              <h2 style="color: #fff;">Bienvenue chez SINFED Technologies, votre partenaire incontournable pour une 
                éducation informatique pratique à Abidjan.
                Forts de notre engagement envers la qualité, nous proposons des formations innovantes
                et adaptées aux besoins actuels du marché du travail.
                Que vous cherchiez à renforcer vos compétences informatiques ou à explorer de nouveaux horizons technologiques, 
                nos programmes axés sur la pratique sont conçus pour conduire vers le succès.
                Rejoignez-nous et transformez vos ambitions numériques en réalité tangible.
              </h2>
              <div class="d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
            <a href="../SINFED/users_area/register.php" class="btn-book-a-table">S'inscrire dès maintenant</a>
          </div>
            </div>
          </div><!-- End Stats Item -->

          

          
        </div>

      </div>
    </section><!-- End Stats Counter Section -->



<!-- ======= Formation Section ======= -->
<section id="formation" class="formation">
  <div class="container" data-aos="fade-up">

    <div class="section-header">
      <h2>Nos Formations</h2>
      <p>Découvrez nos <span>Programmes Éducatifs</span></p>
    </div>

    <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">
      <li class="nav-item">
        <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#formation-primaire">
          <h4>Primaire</h4>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#formation-lycee">
          <h4>Lycée</h4>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#formation-universite">
          <h4>Université</h4>
        </a>
      </li>
    </ul>

    <div class="tab-content" data-aos="fade-up" data-aos-delay="300">

      <!-- Formation Primaire -->
      <div class="tab-pane fade active show" id="formation-primaire">
        <div class="tab-header text-center">
          <p>Formations</p>
          <h3>Primaire</h3>
        </div>

        <ul class="nav nav-pills d-flex justify-content-center">
          <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#cp1">CP1</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#cp2">CP2</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#ce1">CE1</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#ce2">CE2</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#cm1">CM1</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#cm2">CM2</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade active show col-lg-4" id="cp1">
          <a href="../SINFED/assets1/img/primaire/image_CP1.jpeg" class="glightbox"><img src="../SINFED/assets1/img/primaire/image_CP1.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>CP1 - Initiation Informatique</h4>
            <p>Apprendre à utiliser un ordinateur et découvrir les bases du numérique à travers des jeux interactifs.</p>
          </div>
          <div class="tab-pane fade col-lg-4" id="cp2">
          <a href="../SINFED/assets1/img/primaire/image_CP2.jpeg" class="glightbox"><img src="../SINFED/assets1/img/primaire/image_CP2.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>CP2 - Introduction à la Programmation</h4>
            <p>Utilisation de Scratch Junior pour créer des animations et comprendre la logique informatique.</p>
          </div>
          <div class="tab-pane fade col-lg-4" id="ce1">
          <a href="../SINFED/assets1/img/primaire/image_CE1.jpeg" class="glightbox"><img src="../SINFED/assets1/img/primaire/image_CE1.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>CE1 - Internet et Sécurité</h4>
            <p>Apprentissage des bases d'Internet, des bonnes pratiques et des dangers en ligne.</p>
          </div>
          <div class="tab-pane fade col-lg-4" id="ce2">
          <a href="../SINFED/assets1/img/primaire/image_CE2.jpeg" class="glightbox"><img src="../SINFED/assets1/img/primaire/image_CE2.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>CE2 - Création Multimédia</h4>
            <p>Découverte du dessin numérique et des outils de création simples.</p>
          </div>
          <div class="tab-pane fade col-lg-4" id="cm1">
          <a href="../SINFED/assets1/img/primaire/image_CM1.jpeg" class="glightbox"><img src="../SINFED/assets1/img/primaire/image_CM1.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>CM1 - Développement Web Débutant</h4>
            <p>Introduction à HTML et CSS pour créer une première page web.</p>
          </div>
          <div class="tab-pane fade col-lg-4" id="cm2">
          <a href="../SINFED/assets1/img/primaire/image_CM2.jpeg" class="glightbox"><img src="../SINFED/assets1/img/primaire/image_CM2.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>CM2 - Algorithmes et Logique</h4>
            <p>Comprendre les bases de la logique algorithmique avec des exercices interactifs.</p>
          </div>
        </div>
      </div>

      <!-- Formation Lycée -->
      <div class="tab-pane fade" id="formation-lycee">
        <div class="tab-header text-center">
          <p>Formations</p>
          <h3>Lycée</h3>
        </div>

        <ul class="nav nav-pills d-flex justify-content-center">
          <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#sixieme">6ème</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#cinquieme">5ème</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#quatrieme">4ème</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#troisieme">3ème</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#seconde">2nde</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#premiere">1ère</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#terminale">Terminale</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade active show col-lg-4" id="sixieme">
          <a href="../SINFED/assets1/img/lycée/image_6eme.jpg" class="glightbox"><img src="../SINFED/assets1/img/lycée/image_6eme.jpg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>6ème - Programmation Visuelle</h4>
            <p>Initiation à la programmation avec Scratch et introduction aux algorithmes.</p>
          </div>
          <div class="tab-pane fade col-lg-4" id="cinquieme">
          <a href="../SINFED/assets1/img/lycée/image_5eme.jpeg" class="glightbox"><img src="../SINFED/assets1/img/lycée/image_5eme.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>5ème - Internet et Cybersécurité</h4>
            <p>Comprendre le fonctionnement d'Internet et apprendre les bonnes pratiques en cybersécurité.</p>
          </div>
          <div class="tab-pane fade col-lg-4" id="quatrieme">
          <a href="../SINFED/assets1/img/lycée/image_4eme.jpeg" class="glightbox"><img src="../SINFED/assets1/img/lycée/image_4eme.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>4ème - Développement Web Débutant</h4>
            <p>Apprendre à créer des sites web simples avec HTML et CSS.</p>
          </div>
          <div class="tab-pane fade col-lg-4" id="troisieme">
          <a href="../SINFED/assets1/img/lycée/image_3eme1.jpeg" class="glightbox"><img src="../SINFED/assets1/img/lycée/image_3eme1.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>3ème - Python et Automatisation</h4>
            <p>Introduction à la programmation avec Python et utilisation des algorithmes.</p>
          </div>
          <div class="tab-pane fade col-lg-4" id="seconde">
          <a href="../SINFED/assets1/img/lycée/image_1ere.jpeg" class="glightbox"><img src="../SINFED/assets1/img/lycée/image_1ere.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>2nde - Développement Mobile</h4>
            <p>Créer des applications mobiles simples avec App Inventor.</p>
          </div>
          <div class="tab-pane fade col-lg-4" id="premiere">
          <a href="../SINFED/assets1/img/lycée/image_2nd.jpeg" class="glightbox"><img src="../SINFED/assets1/img/lycée/image_2nd.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>1ère - Bases de Données</h4>
            <p>Introduction aux bases de données et manipulation avec SQL.</p>
          </div>
          <div class="tab-pane fade col-lg-4" id="terminale">
          <a href="../SINFED/assets1/img/lycée/image_terminal.jpeg" class="glightbox"><img src="../SINFED/assets1/img/lycée/image_terminal.jpeg" class="menu-img img-fluid rounded" alt="Image arrondie"></a>

            <h4>Terminale - Projet Informatique</h4>
            <p>Créer un projet complet en programmation pour préparer aux études supérieures.</p>
          </div>
        </div>
      </div>

      <!-- Formation Université -->
      <div class="tab-pane fade" id="formation-universite">
        <div class="tab-header text-center">
          <p>Formations</p>
          <h3>Université</h3>
        </div>
        <div class="row gy-5">
          <div class="col-lg-4 formation-item">
            <img src="assets1/img/université/image_université_1.jpeg" class="formation-img img-fluid rounded" alt="Image arrondie">
            <h4>Développement Full Stack</h4>
            <p>Maîtriser le développement web avec JavaScript, Node.js et bases de données.</p>
          </div>
          <div class="col-lg-4 formation-item">
            <img src="assets1/img/université/image_université_2.jpeg" class="formation-img img-fluid rounded" alt="Image arrondie">
            <h4>Cybersécurité Avancée</h4>
            <p>Approfondir les concepts de sécurité informatique et hacking éthique.</p>
          </div>
          <div class="col-lg-4 formation-item">
            <img src="assets1/img/université/image_université_3.jpeg" class="formation-img img-fluid rounded" alt="Image arrondie">
            <h4>Intelligence Artificielle</h4>
            <p>Apprendre le Machine Learning et l'analyse des données massives.</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section><!-- End Formation Section -->

    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Temoignage</h2>
          <p>Découvrez Ce Que Disent Nos Apprenants Sur La Formation De <span>SINFED TECHNOLOGIES</span></p>
        </div>

        <div class="slides-1 swiper" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Après avoir suivi SINFED Technologies, mes compétences informatiques sont plus pointues que jamais ! 
                        Les projets concrets et le soutien des enseignants ont fait toute la différence.
                        Je me sens désormais mieux armé pour relever les défis et exceller dans ma carrière.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Giovanni PARAZONI</h3>
                      <h4>Ceo &amp; Founder</h4>
                      <div class="stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/testimonials/testimonials-1.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        SINFED Technologies a transformé mes compétences informatiques grâce à ses cours stimulants et pratiques.
                        L'approche pratique et les formateurs experts ont facilité l'apprentissage, améliorant considérablement mes persperctives de carrière.
                        Je les recommande vivement à tous ceux qui souhaitent progresser dans le secteur informatique.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Sara Wilsson</h3>
                      <h4>Designer</h4>
                      <div class="stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/testimonials/testimonials-2.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        SINFED Technologies m'a apporté une expérience pratique inestimable.
                        les formateurs étaient compétents et les cours bien structuuré J'ai acquis des compétences pratiques qui ont considérablement renforcé ma confiance dans le domaine informatique.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Jena Karlis</h3>
                      <h4>Store Owner</h4>
                      <div class="stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/testimonials/testimonials-3.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        SINFED Technologies a transformé ma compréhension de l'informatique.
                        Les cours sont axés sur la pratique et les formateurs sont toujours disponibles pour aider.
                        Grâce à eux, j'ai acquis des compétences précieuses qui m'ont ouvert de nouvelles opportunités professionnelles.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>John Larson</h3>
                      <h4>Entrepreneur</h4>
                      <div class="stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/testimonials/testimonials-4.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Testimonials Section -->
        




    <!-- ======= Gallery Section ======= -->
    <section id="gallery" class="gallery section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>gallery</h2>
          <p>Check <span>Our Gallery</span></p>
        </div>

        <div class="gallery-slider swiper">
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-1.jpg"><img src="assets/img/gallery/gallery-1.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-2.jpg"><img src="assets/img/gallery/gallery-2.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-3.jpg"><img src="assets/img/gallery/gallery-3.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-4.jpg"><img src="assets/img/gallery/gallery-4.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-5.jpg"><img src="assets/img/gallery/gallery-5.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-6.jpg"><img src="assets/img/gallery/gallery-6.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-7.jpg"><img src="assets/img/gallery/gallery-7.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-8.jpg"><img src="assets/img/gallery/gallery-8.jpg" class="img-fluid" alt=""></a></div>
          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Gallery Section -->


 


    <!-- ======= Nos Autres Activités Section ======= -->
    <section id="events" class="events">
      <div class="container-fluid" data-aos="fade-up">

        <div class="section-header">
          <h2>Nos Autres Activités</h2>
          <p>Informatique et <span>Innovation</span> dans divers secteurs</p>
          </div>

        <div class="slides-3 swiper" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper-wrapper">

            <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(assets1/Immobilier.jpeg)">
            <h3>Informatique dans l'Immobilier</h3>
          <p class="description">
            Solutions numériques pour la gestion des biens immobiliers : plateformes en ligne, visites virtuelles, automatisation des transactions et gestion locative intelligente.
          </p>
            </div><!-- End Event item -->

            <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(assets1/Élevage.jpeg)">
            <h3>Informatique dans l'Élevage</h3>
          <p class="description">
            Outils technologiques pour le suivi du bétail, la gestion des ressources, l’optimisation des rendements et la mise en place de systèmes intelligents d’élevage.
          </p>
            </div><!-- End Event item -->

            <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(assets1/Énergie_renouvelable.jpeg)">
            <h3>Informatique dans l'Énergies Renouvelables</h3>
          <p class="description">
            Utilisation des technologies pour optimiser la gestion de l’énergie solaire, éolienne et hydraulique : monitoring, maintenance prédictive et automatisation.
          </p>
            </div><!-- End Event item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Nos Autres Activités Section -->



    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Contact</h2>
          <p>Besoin d'aide? <span>Contacter Nous</span></p>
        </div>

        <div class="mb-3">
  <iframe 
    style="border:0; width: 100%; height: 350px;" 
    src="https://maps.google.com/maps?q=5.300833,-3.967889&z=15&output=embed" 
    frameborder="0" allowfullscreen>
  </iframe>
</div>

        <div class="row gy-4">

          <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-map flex-shrink-0"></i>
              <div>
                <h3>Our Address</h3>
                <p>A108 Adam Street, New York, NY 535022</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center">
              <i class="icon bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Email Us</h3>
                <p>castelliparazoni1977@gmail.com</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Call Us</h3>
                <p>+225 0142323822</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-share flex-shrink-0"></i>
              <div>
                <h3>Opening Hours</h3>
                <div><strong>Mon-Sat:</strong> 11AM - 23PM;
                  <strong>Sunday:</strong> Closed
                </div>
              </div>
            </div>
          </div><!-- End Info Item -->

        </div>

        <form action="forms/contact.php" method="post" role="form" class="php-email-form p-3 p-md-4">
          <div class="row">
            <div class="col-xl-6 form-group">
              <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
            </div>
            <div class="col-xl-6 form-group">
              <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
            </div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
          </div>
          <div class="form-group">
            <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
          </div>
          <div class="my-3">
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your message has been sent. Thank you!</div>
          </div>
          <div class="text-center"><button type="submit">Send Message</button></div>
        </form><!--End Contact Form -->

      </div>
    </section><!-- End Contact Section -->



<!-- Contenu de la page -->
<?php include 'includes/footer.php'; ?>

<!-- Script combiné -->
  <script>
    // Animation du texte pour la mission
    let missionText = "Devenir un leader dans l’éducation numérique en Afrique, en initiant un changement durable et en s’étendant progressivement à d’autres pays de la sous-région.";
    let missionIndex = 0;
    const missionElement = document.getElementById("messageMission");

    function typeMissionText() {
      if (missionIndex < missionText.length) {
        missionElement.innerHTML += missionText.charAt(missionIndex);
        missionIndex++;
        setTimeout(typeMissionText, 100);
      } else {
        setTimeout(restartMissionTyping, 60000);
      }
    }

    function restartMissionTyping() {
      missionIndex = 0;
      missionElement.innerHTML = "";
      typeMissionText();
    }

    // Animation du texte pour SINFED
    let text = "Rejoignez SINFED, la référence en éducation avec des cours pratiques d'informatique pour tous les niveaux scolaires.";
    let index = 0;
    const messageElement = document.getElementById("message");

    function typeText() {
      if (index < text.length) {
        messageElement.innerHTML += text.charAt(index);
        index++;
        setTimeout(typeText, 100);
      } else {
        setTimeout(restartTyping, 60000);
      }
    }

    function restartTyping() {
      index = 0;
      messageElement.innerHTML = "";
      typeText();
    }

    // Lancer les deux animations une fois la page chargée
    window.onload = function() {
      typeMissionText();  // Lancer l'animation du texte de la mission
      typeText();         // Lancer l'animation de SINFED
    };
  </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  <!-- Inclure le CSS d'Owl Carousel -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

<!-- Inclure le JS d'Owl Carousel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

</body>
</html>