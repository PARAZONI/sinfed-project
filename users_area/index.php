<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SINFED Technologies - Formation Pratique en Informatique</title>
  <style>
    /* Reset */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body {
      font-family: 'Arial', sans-serif;
      line-height: 1.6;
      color: #333;
    }
    
    header {
      background: #333;
      color: #fff;
      padding: 20px 0;
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
    }
    
    .container {
      width: 90%;
      max-width: 1200px;
      margin: auto;
      overflow: hidden;
    }
    
    nav ul {
      list-style: none;
      text-align: right;
    }
    
    nav ul li {
      display: inline;
      margin-left: 20px;
    }
    
    nav ul li a {
      color: #fff;
      text-decoration: none;
      font-weight: bold;
    }
    
    /* Section Hero avec slideshow de fond */
    .hero {
      position: relative;
      height: 100vh;
      overflow: hidden;
      margin-top: 80px; /* Espace pour l'en-tête fixe */
      text-align: center;
      color: #fff;
    }
    
    /* Container des slides */
    .hero-slides {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
    }
    
    .hero-slide {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      opacity: 0;
      animation: slideAnimation 15s infinite;
    }
    
    /* Attribution des images et décalages pour le cycle */
    .hero-slide:nth-child(1) {
      background-image: url('../assets1/image_voir_cours.jpeg');
      animation-delay: 0s;
    }
    .hero-slide:nth-child(2) {
      background-image: url('../assets1/image_fond_3.jpeg');
      animation-delay: 5s;
    }
    .hero-slide:nth-child(3) {
      background-image: url('../assets1/image_fond_2.jpeg');
      animation-delay: 10s;
    }
    .hero-slide:nth-child(4) {
      background-image: url('../assets1/image\ _fond_1.jpeg');
      animation-delay: 10s;
    }
    
    @keyframes slideAnimation {
      0%   { opacity: 0; }
      10%  { opacity: 1; }
      30%  { opacity: 1; }
      40%  { opacity: 0; }
      100% { opacity: 0; }
    }
    
    /* Contenu de la section hero */
    .hero-content {
      position: relative;
      z-index: 2;
      padding: 0 20px;
      top: 50%;
      transform: translateY(-50%);
    }
    
    .hero h1 {
      font-size: 3rem;
      margin-bottom: 20px;
    }
    
    .hero p {
      font-size: 1.2rem;
      margin-bottom: 40px;
    }
    
    .btn {
      display: inline-block;
      background: #e67e22;
      color: #fff;
      padding: 10px 30px;
      text-decoration: none;
      font-size: 1.2rem;
      border-radius: 5px;
      transition: background 0.3s;
    }
    
    .btn:hover {
      background: #cf711f;
    }
    
    /* Sections Générales */
    section {
      padding: 80px 0;
    }
    
    .section-bg {
      background: url('https://via.placeholder.com/1920x1080') no-repeat center center/cover;
      background-attachment: fixed;
      color: #fff;
      position: relative;
    }
    
    .section-bg::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1;
    }
    
    .section-content {
      position: relative;
      z-index: 2;
    }
    
    h2 {
      text-align: center;
      margin-bottom: 40px;
      font-size: 2.5rem;
    }
    
    .advantages, .formations {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
    }
    
    .advantage, .formation {
      flex: 1 1 300px;
      margin: 20px;
      background: #f4f4f4;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      transition: transform 0.3s;
    }
    
    .advantage:hover, .formation:hover {
      transform: translateY(-10px);
    }
    
    .advantage img, .formation img {
      width: 80px;
      margin-bottom: 20px;
    }
    
    /* Section d'inscription */
    #inscription {
      background: #e67e22;
      color: #fff;
      text-align: center;
      padding: 60px 0;
    }
    
    /* Footer */
    footer {
      background: #333;
      color: #fff;
      text-align: center;
      padding: 20px 0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .hero h1 { font-size: 2rem; }
      nav ul li { display: block; margin: 10px 0; }
      nav ul { text-align: center; }
      .advantages, .formations { flex-direction: column; align-items: center; }
    }
  </style>
</head>
<body>
  <!-- En-tête -->
  <header>
    <div class="container">
      <h1 style="float:left;">SINFED Technologies</h1>
      <nav style="float:right;">
        <ul>
          <li><a href="#accueil">Accueil</a></li>
          <li><a href="#a-propos">À propos</a></li>
          <li><a href="#formations">Formations</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>
      <div style="clear:both;"></div>
    </div>
  </header>

  <!-- Section Hero avec slideshow de fond -->
  <section class="hero" id="accueil">
    <!-- Conteneur des images de fond -->
    <div class="hero-slides">
      <div class="hero-slide"></div>
      <div class="hero-slide"></div>
      <div class="hero-slide"></div>
      <div class="hero-slide"></div>

    </div>
    <!-- Contenu de la section Hero -->
    <div class="hero-content container">
      <h1>Boostez votre avenir avec SINFED Technologies</h1>
      <p>Formation complète en informatique axée sur la pratique, adaptée à tous les niveaux d'apprentissage.</p>
      <a href="#inscription" class="btn">S'inscrire dès maintenant</a>
    </div>
  </section>

  <!-- Section À propos -->
  <section id="a-propos">
    <div class="container section-content">
      <h2>À propos de SINFED Technologies</h2>
      <p>
        SINFED Technologies propose une formation en informatique innovante qui s'adresse aux élèves du primaire aux étudiants. 
        Notre approche pratique vous permet d'acquérir des compétences concrètes et opérationnelles pour réussir dans le domaine technologique.
      </p>
    </div>
  </section>

  <!-- Section Avantages -->
  <section class="section-bg" id="avantages">
    <div class="container section-content">
      <h2>Pourquoi choisir SINFED?</h2>
      <div class="advantages">
        <div class="advantage">
          <img src="../assets1/" alt="Approche Pratique">
          <h3>Approche Pratique</h3>
          <p>Apprenez en réalisant des projets concrets et des cas d’étude réels.</p>
        </div>
        <div class="advantage">
          <img src="https://via.placeholder.com/80" alt="Accompagnement">
          <h3>Accompagnement Personnalisé</h3>
          <p>Un suivi individualisé pour vous guider pas à pas dans votre apprentissage.</p>
        </div>
        <div class="advantage">
          <img src="https://via.placeholder.com/80" alt="Flexibilité">
          <h3>Flexibilité</h3>
          <p>Des formations adaptées à chaque niveau, du primaire au supérieur.</p>
        </div>
      </div>
    </div>
  </section>

<!-- Section Nos Formations -->
<section id="formations">
  <div class="container section-content">
    <h2>Nos Formations</h2>
    <div class="formations-grid">
      <!-- Carte Formation Niveau Primaire -->
      <div class="formation-card" data-level="primaire">
        <img src="../assets1/niveau_primaire.jpeg" alt="Primaire">
        <h3>Niveau Primaire</h3>
        <p>Initiation ludique aux bases de l'informatique pour les plus jeunes.</p>
        <button class="btn details-btn" data-target="modal-primaire">En savoir plus</button>
      </div>
      <!-- Carte Formation Niveau Lycée -->
      <div class="formation-card" data-level="lycee">
        <img src="../assets1/niveau_lycée.jpeg" alt="Lycée">
        <h3>Niveau Lycée</h3>
        <p>Renforcement des bases et introduction aux technologies modernes.</p>
        <button class="btn details-btn" data-target="modal-lycee">En savoir plus</button>
      </div>
      <!-- Carte Formation Niveau Supérieur -->
      <div class="formation-card" data-level="superieur">
        <img src="../assets1/niveau_supérieur.jpeg" alt="Supérieur">
        <h3>Niveau Supérieur</h3>
        <p>Formation professionnelle avec projets réels et outils spécialisés.</p>
        <button class="btn details-btn" data-target="modal-superieur">En savoir plus</button>
      </div>
    </div>
  </div>

  <!-- Modale pour Niveau Primaire -->
  <div id="modal-primaire" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Niveau Primaire</h3>
      <p>
        La formation au niveau primaire vise à familiariser les enfants avec les bases de l'informatique à travers des jeux et des activités ludiques, favorisant ainsi leur curiosité et leur créativité.
      </p>
    </div>
  </div>

  <!-- Modale pour Niveau Lycée -->
  <div id="modal-lycee" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Niveau Lycée</h3>
      <p>
        Au niveau lycée, la formation est conçue pour renforcer les compétences logiques et techniques. Les élèves découvrent des projets concrets et apprennent à utiliser divers outils technologiques pour se préparer aux défis futurs.
      </p>
    </div>
  </div>

  <!-- Modale pour Niveau Supérieur -->
  <div id="modal-superieur" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Niveau Supérieur</h3>
      <p>
        Pour les étudiants, la formation supérieure propose une immersion dans des projets réels avec une approche professionnelle. Les cours intègrent l'utilisation d'outils spécialisés et des cas pratiques afin de préparer efficacement au marché du travail.
      </p>
    </div>
  </div>
</section>

<!-- Styles CSS pour la grille et les modales -->
<style>
  .formations-grid {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 40px;
  }
  .formation-card {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    width: 300px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
  }
  .formation-card:hover {
    transform: translateY(-10px);
  }
  .formation-card img {
    width: 200px;
    margin-bottom: 1px;
  }
  /* Style des fenêtres modales */
  .modal {
    display: none; 
    position: fixed; 
    z-index: 1001; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto; 
    background-color: rgba(0, 0, 0, 0.6);
  }
  .modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 500px;
    position: relative;
  }
  .modal-content h3 {
    margin-bottom: 10px;
  }
  .modal-content p {
    margin-bottom: 20px;
  }
  .close {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
  }
</style>

<!-- Script JavaScript pour gérer l'ouverture et la fermeture des modales -->
<script>
  // Ouvre la modale correspondante lorsque le bouton est cliqué
  document.querySelectorAll('.details-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var targetId = btn.getAttribute('data-target');
      var modal = document.getElementById(targetId);
      if (modal) {
        modal.style.display = 'block';
      }
    });
  });

  // Ferme la modale lorsque l'utilisateur clique sur le "x"
  document.querySelectorAll('.modal .close').forEach(function(span) {
    span.addEventListener('click', function() {
      var modal = span.closest('.modal');
      modal.style.display = 'none';
    });
  });

  // Ferme la modale si l'utilisateur clique en dehors du contenu
  window.addEventListener('click', function(event) {
    document.querySelectorAll('.modal').forEach(function(modal) {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
  });
</script>

  <!-- Section Inscription -->
  <section id="inscription">
    <div class="container">
      <h2>Inscrivez-vous dès maintenant!</h2>
      <p>Rejoignez la communauté SINFED Technologies et propulsez votre carrière dans l’informatique.</p>
      <a href="formulaire_inscription.html" class="btn" style="background: #fff; color: #e67e22;">S'inscrire</a>
    </div>
  </section>

  <!-- Footer / Contact -->
  <footer id="contact">
    <div class="container">
      <p>© 2025 SINFED Technologies. Tous droits réservés.</p>
      <p>Email : contact@sinfedtech.com | Téléphone : +33 1 23 45 67 89</p>
    </div>
  </footer>

  <!-- Script pour animation au défilement -->
  <script>
    const scrollElements = document.querySelectorAll(".section-content");

    const elementInView = (el, dividend = 1) => {
      const elementTop = el.getBoundingClientRect().top;
      return elementTop <= (window.innerHeight || document.documentElement.clientHeight) / dividend;
    };

    const displayScrollElement = (element) => {
      element.classList.add('scrolled');
    };

    const hideScrollElement = (element) => {
      element.classList.remove('scrolled');
    };

    const handleScrollAnimation = () => {
      scrollElements.forEach((el) => {
        if (elementInView(el, 1.25)) {
          displayScrollElement(el);
        } else {
          hideScrollElement(el);
        }
      });
    };

    window.addEventListener("scroll", handleScrollAnimation);
  </script>
</body>
</html>