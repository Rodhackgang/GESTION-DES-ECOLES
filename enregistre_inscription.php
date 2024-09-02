<?php
require_once './connexion/dbcon.php'; // Inclure le fichier de connexion à la base de données
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function emailExists($email) {
    global $conn;

    $email = mysqli_real_escape_string($conn, $email);

    $sql = "SELECT COUNT(*) as count FROM utilisateurs WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    } else {
        die('Erreur de requête: ' . $conn->error);
    }
}
function adminExists() {
    global $conn;

    $sql = "SELECT COUNT(*) as count FROM utilisateurs WHERE role = 'Admin'";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    } else {
        die('Erreur de requête: ' . $conn->error);
    }
}


function createAccount($username, $password, $email, $role) {
    global $conn; // Accéder à la connexion à la base de données définie dans db_connection.php

    // Vérifier si l'email existe déjà
    if (emailExists($email)) {
        return 'Email déjà utilisé.'; // Email déjà présent dans la base de données
    }

    // Échapper les entrées pour éviter les injections SQL
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    $email = mysqli_real_escape_string($conn, $email);
    $role = mysqli_real_escape_string($conn, $role);

    // Hasher le mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Requête SQL préparée pour insérer les données dans la table des utilisateurs
    $stmt = $conn->prepare("INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe, email, role) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $username, $hashed_password, $email, $role);
        if ($stmt->execute()) {
            return true; // Succès
        } else {
            die('Erreur lors de l\'exécution: ' . $stmt->error);
        }
    } else {
        die('Erreur de préparation: ' . $conn->error);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les champs sont vides
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['role'])) {
        $message = "Tous les champs sont obligatoires.";
    } else {
        // Récupérer les données du formulaire
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        // Vérifier si l'utilisateur est un administrateur
        if ($role == 'Admin') {
            // Si l'utilisateur est un administrateur, insérer les données sans vérifier le nombre d'administrateurs
            $creationResult = createAccount($username, $password, $email, $role);
            if ($creationResult === true) {
                $message = "Compte créé avec succès. Connectez-vous, s'il vous plaît.";
            } else {
                $message = $creationResult; // Afficher le message d'erreur approprié
            }
        } else {
            // Si l'utilisateur n'est pas un administrateur, vérifier s'il existe au moins un administrateur dans la base de données
            if (adminExists()) {
                // Si au moins un administrateur existe, insérer les données
                $creationResult = createAccount($username, $password, $email, $role);
                if ($creationResult === true) {
                    $message = "Compte créé avec succès. Connectez-vous, s'il vous plaît.";
                } else {
                    $message = $creationResult; // Afficher le message d'erreur approprié
                }
            } else {
                // Si aucun administrateur n'existe, afficher un message d'erreur
                $message = "Système erreur: aucun administrateur trouvé. Contactez l'administrateur système.";
            }
        }
    }
}



// Rediriger avec le message
header('Location: ./index.php?message=' . urlencode($message));
exit();
?>
