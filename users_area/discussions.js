$(document).ready(function () {
    // Charger les discussions au chargement de la page
    loadDiscussions();

    // Soumettre une nouvelle question via AJAX
    $('#questionForm').on('submit', function (e) {
        e.preventDefault();
        const question = $('#question').val();

        $.ajax({
            url: 'submit_question.php',
            type: 'POST',
            data: { question: question },
            success: function (response) {
                $('#question').val(''); // Réinitialiser le champ
                loadDiscussions(); // Recharger les discussions
            },
            error: function () {
                alert('Erreur lors de la soumission de la question.');
            }
        });
    });

    // Fonction pour charger les discussions
    function loadDiscussions() {
        $.ajax({
            url: 'fetch_discussions.php',
            type: 'GET',
            success: function (response) {
                $('#discussionsList').html(response);
            },
            error: function () {
                alert('Erreur lors du chargement des discussions.');
            }
        });
    }
});