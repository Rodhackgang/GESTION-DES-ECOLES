<?php
require_once './connexion/dbcon.php'; // Inclure le fichier de connexion à la base de données

function verifyLogin($email, $password) {
    global $conn; // Accéder à la connexion à la base de données définie dans dbcon.php

    // Échapper les entrées pour éviter les injections SQL
    $email = mysqli_real_escape_string($conn, $email);

    // Requête SQL pour récupérer les informations de l'utilisateur
    $sql = "SELECT * FROM utilisateurs WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['mot_de_passe'])) {
            return $user; // Retourner les informations de l'utilisateur si le mot de passe est correct
        }
    }
    return false; // Retourner faux si l'utilisateur n'est pas trouvé ou si le mot de passe est incorrect
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

function getStaffRole($userId) {
    global $conn;

    // Requête SQL pour récupérer le rôle du staff à partir de la table staffs
    $sql = "SELECT role FROM staffs WHERE id = (SELECT id FROM staffs WHERE email = (SELECT email FROM utilisateurs WHERE id = $userId))";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        return $staff['role'];
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les champs sont vides
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $message = "Tous les champs sont obligatoires.";
    } else {
        if (!adminExists()) {
            $message = "Système erreur: aucun administrateur trouvé. Contactez l'administrateur système.";
        } else {
            // Récupérer les données du formulaire
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Appeler la fonction pour vérifier la connexion
            $user = verifyLogin($email, $password);
            if ($user) {
                session_start(); // Démarrer la session si ce n'est pas déjà fait
                $_SESSION['logged_in'] = true;
                $_SESSION['utilisateur'] = $user['nom_utilisateur'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email']; // Stocker l'email dans la session
                $_SESSION['utilisateur_id'] = $user['id'];

                // Rediriger vers le tableau de bord approprié en fonction du rôle
                if ($user['role'] == 'Admin') {
                    header('Location: ./Dashboard_Admin/index.php');
                } elseif ($user['role'] == 'Staff') {
                    $staffRole = getStaffRole($user['id']);
                    if ($staffRole == 'Censeur') {
                        header('Location: ./Dashboard_Admin/index.php');
                    } elseif ($staffRole == 'Enseignant') {
                        header('Location: ./Dashboard_Staff/Enseigants/index.php');
                    } elseif ($staffRole == 'Surveillant') {
                        header('Location: ./Dashboard_Staff/Surveillant/index.php');
                    } elseif ($staffRole == 'Superviseur') {
                        header('Location: ./Dashboard_Staff/Proviseur/index.php');
                    } else {
                        $message = "Rôle de staff non reconnu.";
                        header('Location: ./index.php?message=' . urlencode($message));
                        exit();
                    }
                } elseif ($user['role'] == 'Eleve') {
                    header('Location: ./Dasboard_Eleves/eleve_dashboard.php');
                } else {
                    // Gérer les cas où le rôle n'est pas défini ou invalide
                    $message = "Rôle non reconnu.";
                    header('Location: ./index.php?message=' . urlencode($message));
                    exit();
                }
                exit();
            } else {
                $message = "Email ou mot de passe incorrect.";
            }
        }
    }
    // Rediriger avec le message d'erreur
    header('Location: ./index.php?message=' . urlencode($message));
    exit(); // Arrêter le script
}
?>
