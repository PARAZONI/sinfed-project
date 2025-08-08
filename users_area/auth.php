<?php
// Activer les erreurs pour le développement (désactiver en production)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification - SINFED Academy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">SINFED Academy - Authentification</h1>
        
        <!-- Choix de l'action -->
        <ul class="nav nav-tabs" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="true">
                    S'inscrire
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="false">
                    Se connecter
                </button>
            </li>
        </ul>

        <div class="tab-content mt-4" id="authTabsContent">
            <!-- Formulaire d'inscription -->
            <div class="tab-pane fade show active" id="register" role="tabpanel" aria-labelledby="register-tab">
                <form action="register.php" method="POST" class="p-4 border rounded" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="student">Élève</option>
                            <option value="trainer">Formateur</option>
                        </select>
                    </div>

                    <!-- Ajout d'une description pour le formateur -->
                    <div class="mb-3" id="trainerDescriptionDiv" style="display: none;">
                        <label for="trainerDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="trainerDescription" name="trainer_description"></textarea>
                    </div>

                    <!-- Ajout d'un CV pour le formateur -->
                    <div class="mb-3" id="cvDiv" style="display: none;">
                        <label for="cv" class="form-label">Téléchargez votre CV</label>
                        <input type="file" class="form-control" id="cv" name="cv">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                </form>
            </div>

            <!-- Formulaire de connexion -->
            <div class="tab-pane fade" id="login" role="tabpanel" aria-labelledby="login-tab">
                <form action="login.php" method="POST" class="p-4 border rounded">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="loginEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="loginPassword" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Afficher ou masquer les champs de description et CV si le rôle est formateur
        document.getElementById('role').addEventListener('change', function() {
            var role = this.value;
            var descriptionDiv = document.getElementById('trainerDescriptionDiv');
            var cvDiv = document.getElementById('cvDiv');
            
            if (role === 'trainer') {
                descriptionDiv.style.display = 'block';
                cvDiv.style.display = 'block';
            } else {
                descriptionDiv.style.display = 'none';
                cvDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>