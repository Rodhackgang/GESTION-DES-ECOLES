<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de configuration de la base de données
    include_once "../../connexion/dbcon.php";

    // Récupérer les valeurs du formulaire
    $nom_personnel = $_POST['nom_personnel'];
    $prenom_personnel = $_POST['prenom_personnel'];
    $role_personnel = $_POST['role_personnel'];
    $responsabilites = $_POST['responsabilites'];
    $horaires = $_POST['horaires'];
    $disponibilite = $_POST['disponibilite'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $date_embauche = $_POST['date_embauche'];

    // Pour les enseignants, récupérer l'ID de la classe
    $classe_id = null;
    if ($role_personnel == 'enseignant' && isset($_POST['classe_id'])) {
        $classe_id = $_POST['classe_id'];
        
        // Vérifier si l'ID de la classe existe dans la table salles_classe
        $query_check_classe = "SELECT COUNT(*) AS count FROM salles_classe WHERE id = ?";
        if ($stmt_check_classe = $conn->prepare($query_check_classe)) {
            $stmt_check_classe->bind_param("i", $classe_id);
            $stmt_check_classe->execute();
            $result_check_classe = $stmt_check_classe->get_result();
            $row_check_classe = $result_check_classe->fetch_assoc();
            $count_classe = $row_check_classe['count'];
            $stmt_check_classe->close();

            // Si la classe n'existe pas, afficher une erreur et rediriger
            if ($count_classe == 0) {
                $_SESSION['message'] = "La classe sélectionnée n'existe pas.";
                header("location: ../gestion_personnel.php");
                exit();
            }
        }
    }

    // Vérifier si l'e-mail existe déjà dans la base de données
    $query_check_email = "SELECT COUNT(*) AS count FROM staffs WHERE email = ?";
    if ($stmt_check_email = $conn->prepare($query_check_email)) {
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $result_check_email = $stmt_check_email->get_result();
        $row_check_email = $result_check_email->fetch_assoc();
        $count_email = $row_check_email['count'];
        $stmt_check_email->close();

        // Si l'e-mail existe déjà, afficher une erreur et rediriger
        if ($count_email > 0) {
            $_SESSION['message'] = "L'e-mail existe déjà dans la base de données.";
            header("location: ../gestion_personnel.php");
            exit();
        }
    }

    // Préparer la requête d'insertion
    $query = "INSERT INTO staffs (nom, prenom, role, telephone, email, date_embauche, classe_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    // Préparer et exécuter la requête avec les valeurs fournies
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssssssi", $nom_personnel, $prenom_personnel, $role_personnel, $telephone, $email, $date_embauche, $classe_id);
        if ($stmt->execute()) {
            // Si l'insertion est réussie, rediriger vers la page principale avec un message
            $_SESSION['message'] = "Le personnel a été ajouté avec succès.";
            header("location: ../gestion_personnel.php");
            exit();
        } else {
            // En cas d'erreur lors de l'insertion, afficher un message d'erreur
            $_SESSION['message'] = "Une erreur s'est produite lors de l'ajout du personnel.";
            header("location: ../gestion_personnel.php");
            exit();
        }
        $stmt->close();
    }

    // Fermer la connexion à la base de données
    $conn->close();
} else {
    // Si le formulaire n'a pas été soumis, rediriger vers la page principale
    header("location: ../gestion_personnel.php");
    exit();
}
?>
