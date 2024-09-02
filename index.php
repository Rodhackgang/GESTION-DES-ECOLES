<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est déjà connecté
/*
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Rediriger vers la page d'accueil
    header('Location: ./Acceuil/index.php');
    exit();
}
*/
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Page de Connexion Moderne | AsmrProg</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="./enregistre_inscription.php" method="POST">
                <h1>Créer un Compte</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>ou utilisez votre e-mail pour vous inscrire</span>
                <input type="text" placeholder="Nom" name="username">
                <input type="email" placeholder="E-mail" name="email">
                <input type="password" placeholder="Mot de passe" name="password">
                <select name="role" required>
                    <option value="" disabled selected>Choisissez votre rôle</option>
                    <option value="Admin">Admin</option>
                    <option value="Staff">Staff</option>
                    <option value="Eleve">Eleve</option>
                </select>
                <button type="submit">S'inscrire</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="./verifie_inscription.php" method="POST">
                <h1>Se Connecter</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>ou utilisez votre e-mail et votre mot de passe</span>
                <input type="email" placeholder="E-mail" name="email">
                <input type="password" placeholder="Mot de passe" name="password">
                <a href="#">Mot de passe oublié ?</a>
                <button type="submit">Se Connecter</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>De Retour !</h1>
                    <p>Entrez vos coordonnées personnelles pour utiliser toutes les fonctionnalités du site</p>
                    <button class="hidden" id="login">Se Connecter</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Bonjour, Ami !</h1>
                    <p>Inscrivez-vous avec vos coordonnées personnelles pour utiliser toutes les fonctionnalités du site</p>
                    <button class="hidden" id="register">S'inscrire</button>
                    <div id="message"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>