<?php
include('../config/db.php');

// Vérifier si le cours est défini
if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);

    // Requête pour récupérer les étudiants inscrits
    $query = "SELECT s.id, s.first_name, s.last_name 
              FROM students s 
              INNER JOIN enrollments e ON e.student_id = s.id 
              WHERE e.course_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $students = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = [
            'id' => $row['id'],
            'name' => $row['first_name'] . ' ' . $row['last_name']
        ];
    }

    // Envoyer la réponse en JSON
    echo json_encode($students);
    exit();
} else {
    echo json_encode([]);
    exit();
}
?>