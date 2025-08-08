<?php
include('../config/db.php');
session_start();

// Vérifier si le formateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'trainer') {
    echo "Veuillez vous connecter en tant que formateur.";
    exit();
}


$user_id = $_SESSION['user_id'];
// Récupérer les cours du formateur
$query_courses = "SELECT * FROM courses WHERE trainer_id = ?";
$stmt_courses = mysqli_prepare($conn, $query_courses);
mysqli_stmt_bind_param($stmt_courses, "i", $trainer_id);
mysqli_stmt_execute($stmt_courses);
$courses_result = mysqli_stmt_get_result($stmt_courses);

// Afficher le tableau de bord
echo "<h1>Tableau de bord du formateur</h1>";

if (mysqli_num_rows($courses_result) > 0) {
    while ($course = mysqli_fetch_assoc($courses_result)) {
        echo "<div>
                <h2>" . htmlspecialchars($course['title']) . "</h2>
                <p>" . htmlspecialchars($course['description']) . "</p>
                <p><strong>Date de début : </strong>" . htmlspecialchars($course['start_date']) . "</p>
                <p><strong>Statut : </strong>" . htmlspecialchars($course['status']) . "</p>
                <a href='../users_area/view_students.php?course_id=" . $course['course_id'] . "'>Voir les élèves inscrits</a>
              </div><hr>";
    }
} else {
    echo "<p>Vous n'avez pas encore de cours enregistrés.</p>";
}

// Section pour envoyer des messages
echo "<h2>Envoyer un message aux étudiants</h2>";

// Afficher le formulaire pour envoyer un message
echo '<form action="send_message.php" method="post">
        <label for="course_id">Choisir le cours :</label>
        <select name="course_id" id="course_id" required>
            <option value="">-- Sélectionner un cours --</option>';

// Réutilisation de la liste des cours
mysqli_data_seek($courses_result, 0);
while ($course = mysqli_fetch_assoc($courses_result)) {
    echo '<option value="' . $course['course_id'] . '">' . htmlspecialchars($course['title']) . '</option>';
}

echo '</select><br><br>

        <label for="student_id">Choisir un étudiant :</label>
        <select name="student_id" id="student_id" required>
            <option value="">-- Sélectionner un étudiant --</option>
        </select><br><br>

        <label for="message">Message :</label><br>
        <textarea name="message" id="message" rows="4" cols="50" required></textarea><br><br>
        <button type="submit">Envoyer le message</button>
      </form>';

// Afficher les messages envoyés
echo "<h2>Messages envoyés</h2>";

// Requête pour afficher les messages
$query_messages = "SELECT m.id, m.message, m.created_at, s.first_name, s.last_name, c.title 
                   FROM messages m
                   JOIN students s ON m.student_id = s.id
                   JOIN courses c ON m.course_id = c.course_id
                   WHERE c.trainer_id = ? ORDER BY m.created_at DESC";

$stmt_messages = mysqli_prepare($conn, $query_messages);
mysqli_stmt_bind_param($stmt_messages, "i", $trainer_id);
mysqli_stmt_execute($stmt_messages);
$messages_result = mysqli_stmt_get_result($stmt_messages);

if (mysqli_num_rows($messages_result) > 0) {
    while ($message = mysqli_fetch_assoc($messages_result)) {
        echo "<div>
                <strong>Cours :</strong> " . htmlspecialchars($message['title']) . "<br>
                <strong>À :</strong> " . htmlspecialchars($message['first_name']) . " " . htmlspecialchars($message['last_name']) . "<br>
                <strong>Message :</strong> " . nl2br(htmlspecialchars($message['message'])) . "<br>
                <strong>Envoyé le :</strong> " . htmlspecialchars($message['created_at']) . "<br>
              </div><hr>";
    }
} else {
    echo "<p>Aucun message envoyé pour le moment.</p>";
}

// Libérer les ressources
mysqli_stmt_close($stmt_courses);
mysqli_stmt_close($stmt_messages);
mysqli_close($conn);
?>


<script>
document.getElementById('course_id').addEventListener('change', function() {
    const courseId = this.value;
    const studentSelect = document.getElementById('student_id');

    // Réinitialiser la liste des étudiants
    studentSelect.innerHTML = '<option value="">-- Sélectionner un étudiant --</option>';

    if (courseId) {
        // Effectuer une requête AJAX pour récupérer les étudiants inscrits
        fetch(`fetch_students.php?course_id=${courseId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    // Ajouter chaque étudiant à la liste
                    data.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = student.name;
                        studentSelect.appendChild(option);
                    });
                } else {
                    studentSelect.innerHTML = '<option value="">Aucun étudiant inscrit</option>';
                }
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des étudiants:', error);
            });
    }
});
</script>