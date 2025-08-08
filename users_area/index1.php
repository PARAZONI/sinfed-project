<?php
// index.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SINFED Technologies - Accueil</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Styles personnalisés -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .hero {
            background: url('images/banner.jpg') no-repeat center center/cover;
            height: 90vh;
            color: white;
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            flex-direction: column;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .hero p {
            font-size: 1.2rem;
        }
        .service-card {
            transition: transform 0.3s;
        }
        .service-card:hover {
            transform: scale(1.05);
        }
        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">SINFED Technologies</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#about">Présentation</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#vision">Vision</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Section Hero (Accueil) -->
    <header class="hero">
        <h1>Bienvenue chez SINFED Technologies</h1>
        <p>Innovation & Technologie au service de l’avenir</p>
        <a href="#about" class="btn btn-primary btn-lg">Découvrir</a>
    </header>

    <!-- Présentation -->
    <section id="about" class="container my-5">
        <h2 class="text-center">Qui sommes-nous ?</h2>
        <p class="text-center text-muted">SINFED Technologies est une entreprise spécialisée dans les solutions informatiques, l'éducation numérique et les objets connectés.</p>
        <div class="row text-center mt-4">
            <div class="col-md-4">
                <i class="fa fa-laptop fa-3x text-primary"></i>
                <h4>Informatique & Développement</h4>
                <p>Création de logiciels, applications mobiles et solutions digitales sur mesure.</p>
            </div>
            <div class="col-md-4">
                <i class="fa fa-graduation-cap fa-3x text-success"></i>
                <h4>Éducation Numérique</h4>
                <p>Formations, certifications et plateformes e-learning.</p>
            </div>
            <div class="col-md-4">
                <i class="fa fa-microchip fa-3x text-warning"></i>
                <h4>Objets Connectés (IoT)</h4>
                <p>Développement de solutions intelligentes pour l’agriculture, l’énergie et l'immobilier.</p>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section id="services" class="bg-light py-5">
        <div class="container">
            <h2 class="text-center">Nos Services</h2>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card service-card">
                        <div class="card-body text-center">
                            <i class="fa fa-code fa-3x text-primary"></i>
                            <h5 class="card-title mt-3">Développement Web & Mobile</h5>
                            <p class="card-text">Création d'applications web, mobiles et systèmes logiciels.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card">
                        <div class="card-body text-center">
                            <i class="fa fa-cogs fa-3x text-success"></i>
                            <h5 class="card-title mt-3">Intelligence Artificielle</h5>
                            <p class="card-text">Développement de solutions IA et automatisation des processus.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card">
                        <div class="card-body text-center">
                            <i class="fa fa-solar-panel fa-3x text-warning"></i>
                            <h5 class="card-title mt-3">Technologies Solaires</h5>
                            <p class="card-text">Optimisation et gestion intelligente des systèmes solaires.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision -->
    <section id="vision" class="container my-5">
        <h2 class="text-center">Notre Vision</h2>
        <p class="text-center text-muted">Nous visons un monde où la technologie et l’éducation transforment la société en offrant des solutions durables et innovantes.</p>
    </section>

    <!-- Contact -->
    <section id="contact" class="bg-dark text-white py-5">
        <div class="container text-center">
            <h2>Contactez-nous</h2>
            <p>Email: contact@sinfedtech.com | Téléphone: +225 07 00 00 00</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> SINFED Technologies. Tous droits réservés.</p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>