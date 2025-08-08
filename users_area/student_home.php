<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

include('../config/db.php');



$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'élève
$query = "SELECT * FROM students WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

if ($user_data) {
    $_SESSION['user_email'] = $user_data['email'];
}

mysqli_stmt_close($stmt);

// Récupérer les cours de l'élève
$query_courses = "
    SELECT c.title, c.start_date, c.status, sc.progress_course 
    FROM courses c
    JOIN student_courses sc ON c.course_id = sc.course_id
    WHERE sc.student_id = ? AND c.status = 'En Cours'
    ORDER BY c.start_date DESC
";
$stmt_courses = mysqli_prepare($conn, $query_courses);
mysqli_stmt_bind_param($stmt_courses, "i", $user_id);
mysqli_stmt_execute($stmt_courses);
$courses_result = mysqli_stmt_get_result($stmt_courses);

// Calcul de la progression globale
$total_courses = 0;
$total_progress = 0;

while ($row = mysqli_fetch_assoc($courses_result)) {
    $total_courses++;
    $total_progress += $row['progress_course'];
}

// Éviter la division par zéro
$average_progress = ($total_courses > 0) ? round($total_progress / $total_courses, 2) : 0;

mysqli_stmt_close($stmt_courses);

// Récupérer les notifications de l'élève
$query_notifications = "
    SELECT id, message, is_read, created_at 
    FROM notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC
";
$stmt_notifications = mysqli_prepare($conn, $query_notifications);
mysqli_stmt_bind_param($stmt_notifications, "i", $user_id);
mysqli_stmt_execute($stmt_notifications);
$notifications_result = mysqli_stmt_get_result($stmt_notifications);
mysqli_stmt_close($stmt_notifications);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Générer une couleur aléatoire en format rgba
function getRandomColor() {
    var r = Math.floor(Math.random() * 256);
    var g = Math.floor(Math.random() * 256);
    var b = Math.floor(Math.random() * 256);
    return 'rgba(' + r + ',' + g + ',' + b + ', 0.2)';
}

// Changer le type de graphique de manière aléatoire
var chartTypes = ['bar', 'line', 'radar', 'pie'];
var randomChartType = chartTypes[Math.floor(Math.random() * chartTypes.length)];

// Données pour le graphique
var progressData = <?php echo json_encode([70, 80, 90, 100]); ?>; // Remplacer par des données réelles
var chartLabels = ['Cours', 'Leçon', 'Exercices', 'Quiz'];  // Labels

// Configuration du graphique
window.onload = function () {
    var ctx = document.getElementById('progressChart').getContext('2d');
    
    new Chart(ctx, {
        type: randomChartType,  // Type de graphique aléatoire
        data: {
            labels: chartLabels,  // Labels des données
            datasets: [{
                label: 'Progression de l\'élève',
                data: progressData,  // Données du graphique
                backgroundColor: getRandomColor(),  // Couleur de fond aléatoire
                borderColor: getRandomColor(),  // Couleur de bordure aléatoire
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true  // Commencer à zéro sur l'axe Y
                }
            }
        }
    });
};
</script>


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
    <title>Tableau de bord Élève - SINFED Academy</title>
    <link rel="stylesheet" href="../users_area/style_eleve.css">    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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


</head>
<style>

header {
    background-color: #FF5722;
    padding: 10px 0;
}

nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
}

.navbar-brand img {
    height: 150px;
    border-radius: 50%;
}

.navbar-nav {
    display: flex;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
}

.navbar-nav li {
    margin: 0 10px;
}

.navbar-nav a {
    text-decoration: none;
    color: white;
    font-weight: bold;
    display: flex;
    align-items: center;
}

.navbar-nav a i {
    margin-right: 5px;
}

.user-info {
    display: flex;
    align-items: center;
    color: white;
}

.search-bar {
    display: flex;
    align-items: center;
}

.search-bar input {
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.search-bar button {
    background-color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}






    .logo {
        height: 100px;
        border-radius: 360%;
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
    
    /* Ajouter un padding pour l'élément de notification */
    .notification-item {
        padding: 10px;
        background-color: #f9f9f9;
        margin-bottom: 10px;
        border-radius: 5px;
    }
    
    .new {
        background-color: #ffdb58; /* Couleur pour les nouvelles notifications */
    }

    .progress-bar {
        background-color: #f3f3f3;
        height: 30px;
        border-radius: 25px;
        margin: 10px 0;
    }

    .progress-bar-filled {
        background-color: #4caf50;
        height: 100%;
        border-radius: 5px;
    }




nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
}

.navbar-brand {
    display: flex;
    align-items: center;
}

.navbar-brand img {
    height: 100px;
    border-radius: 50%;
    margin-right: 10px;
}

.navbar-nav {
    display: flex;
    align-items: center;
    list-style: none;
    padding: 0;
    margin: 0;
}

.navbar-nav li {
    margin: 0 10px;
}

.navbar-nav a {
    text-decoration: none;
    color: white;
    font-weight: bold;
    display: flex;
    align-items: center;
}

.navbar-nav a i {
    margin-right: 5px;
}

.search-bar {
    display: flex;
    align-items: center;
    margin-right: 10px;
}

.search-bar input {
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-right: 10px;
}

.search-bar button {
    background-color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    
}

/* Pour le dropdown */
.navbar-nav .dropdown {
    position: relative;
}

.navbar-nav .dropdown .submenu {
    display: none;
    position: absolute;
    left: 0;
    top: 100%;
    background-color: white;
    list-style: none;
    padding: 1px 0;
    margin: 0;
    min-width: 18px;
    height: 35px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 1000;
}

.navbar-nav .dropdown:hover .submenu {
    display: block;
}

.navbar-nav .dropdown .submenu li a {
    padding: 8px 15px;
    display: block;
    text-decoration: none;
    color: #333;
}

.navbar-nav .dropdown .submenu li a:hover {
    background-color: #f0f0f0;
}
</style>
<body>

<header>
    <nav>
        <a href="../users_area/student_home.php" class="navbar-brand">
            <img src="../assets1/img/logo/SINFED_Image.jpeg" alt="SINFED" class="logo">
        </a>

        <ul class="navbar-nav">
            <li><a href="../users_area/profile_student.php"><i class="fa-solid fa-user"></i> Mon Profil</a></li>
            <li><a href="../users_area/mes_cours.php"><i class="fa-solid fa-book"></i> Mes Cours</a></li>
            <li><a href="../users_area/enroll_course.php"><i class="fa-solid fa-plus-circle"></i> S'inscrire au cour</a></li>
            <li><a href="../users_area/forum_list.php"><i class="fa-solid fa-comments"></i> Forum</a></li>
            <li><a href="../users_area/send_message.php"><i class="fa-solid fa-envelope"></i> Message</a></li>
            <li class="dropdown">
            <a href="../users_area/view_exercises_élève.php"><i class="fa-solid fa-pen"></i> Exercices</a>
        <ul class="submenu">
           <li><a href="../users_area/mes_quiz.php"><i class="fa-solid fa-question"></i> Quiz</a></li>
          </ul>
</li>            <!-- <li><a href="../deconnexion.php" class="btn btn-danger"><i class="fa-solid fa-sign-out-alt"></i> Déconnexion</a></li> -->
        </ul>

        <div class="user-info">
            <?php if (isset($_SESSION['user_email'])): ?>
                <span class="user-welcome">
                    <i class="fa-solid fa-user-circle"></i> <?php echo $greeting; ?>, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
            <?php else: ?>
                <a href="users_area/auth.php" class="btn"><i class="fa-solid fa-sign-in-alt"></i> Connexion</a>
                <a href="users_area/register.php" class="btn"><i class="fa-solid fa-user-plus"></i> S'inscrire</a>
            <?php endif; ?>
        </div>

        <form class="search-bar " action="../users_area/search_results.php" method="GET">
            <input type="search" name="query" placeholder="Recherche" aria-label="Rechercher">
            <button type="submit"><i class="fa-solid fa-search me-2"></i></button>
        </form>
    </nav>
</header>

<main>
<section>
    <h2>Vos Cours en Cours</h2>
    <li><a href="../users_area/quiz.php?quiz_id=2"><i class="fa-solid fa-comments"></i> Quiz</a></li>

    <div class="cours-list">
        <?php 
        mysqli_data_seek($courses_result, 0); 
        if (mysqli_num_rows($courses_result) > 0) {
            while ($row = mysqli_fetch_assoc($courses_result)) {
        ?>
                <div class="cours-item">
                    <h3><?= htmlspecialchars($row['title']); ?></h3>
                    <p>Date de début : <?= htmlspecialchars($row['start_date']); ?></p>
                    <p>Status : <?= htmlspecialchars($row['status']); ?></p>
                    <p>Progression : <?= htmlspecialchars($row['progress_course']); ?>%</p>
                </div>
        <?php 
            }
        } else {
            echo '<p>Aucun cours en cours disponible.</p>';
        }
        ?>
    </div>
</section>    

<section>
    <h2>Suivi des Progrès</h2>
    <div class="progress">
        <p>Progression moyenne : <strong><?= $average_progress ?>%</strong></p>
        <div class="progress-bar">
            <div class="progress-bar-filled" style="width: <?= $average_progress ?>%;"></div>
        </div>
        <canvas id="progressChart"></canvas> <!-- Le graphique sera ici -->
    </div>
</section>

<section>
    <h2>Notifications</h2>
    <div class="notifications">
        <?php 
        if (mysqli_num_rows($notifications_result) > 0) {
            while ($notification = mysqli_fetch_assoc($notifications_result)) {
                // Vérifier si la notification est lue ou non
                if ($notification['is_read'] == '0') {
                    $status_label = 'Nouvelle notification:';
                    $status_class = 'new'; // Classe pour une notification non lue
                    // Afficher la notification non lue avec le bouton "Marquer comme lue"
                    echo '<div class="notification-item ' . $status_class . '">';
                    echo '<p><strong>' . $status_label . '</strong> ' . htmlspecialchars($notification['message']) . '</p>';
                    echo '<p><small>Le ' . date('d-m-Y H:i', strtotime($notification['created_at'])) . '</small></p>';
                    echo '<form action="mark_as_read1.php" method="POST">';
                    echo '<input type="hidden" name="notification_id" value="' . $notification['id'] . '">';
                    echo '<button type="submit" class="mark-read">Marquer comme lue</button>';
                    echo '</form>';
                    echo '</div>';
                } 
                // Si la notification est lue, ne pas l'afficher
                elseif ($notification['is_read'] == '1') {
                    // La notification lue ne s'affiche pas, donc rien à faire ici
                }
            }
        } else {
            echo '<p>Aucune notification disponible.</p>';
        }
        ?>
    </div>
</section>
</main>

<?php include('../includes/footer.php'); ?>

</body>
</html>