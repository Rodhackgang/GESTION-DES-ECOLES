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
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];
    $prof_principal = $_POST['prof_principal'];

    // Vérifier si le nom de la salle de classe existe déjà
    $query_check_existence = "SELECT COUNT(*) AS count FROM salles_classe WHERE nom = ?";
    if ($stmt_check_existence = $conn->prepare($query_check_existence)) {
        $stmt_check_existence->bind_param("s", $nom);
        $stmt_check_existence->execute();
        $result_check_existence = $stmt_check_existence->get_result();
        $row_check_existence = $result_check_existence->fetch_assoc();
        $count_existence = $row_check_existence['count'];
        $stmt_check_existence->close();

        // Si le nom de la salle existe déjà, renvoyer une erreur dans l'URL
        if ($count_existence > 0) {
            $_SESSION['error'] = "Le nom de la salle de classe existe déjà.";
            header("location: ../gestion_salle.php?error=exists");
            exit();
        }
    }

    // Préparer la requête d'insertion
    $query = "INSERT INTO salles_classe (nom, capacite, prof_principal) VALUES (?, ?, ?)";

    // Préparer et exécuter la requête avec les valeurs fournies
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sis", $nom, $capacite, $prof_principal);
        if ($stmt->execute()) {
            // Si l'insertion est réussie, rediriger vers la page principale avec un message
            $_SESSION['message'] = "La salle de classe a été ajoutée avec succès.";
            header("location: ../gestion_salle.php");
            exit();
        } else {
            // En cas d'erreur lors de l'insertion, afficher un message d'erreur
            $_SESSION['message'] = "Une erreur s'est produite lors de l'ajout de la salle de classe.";
            header("location: ../gestion_salle.php");
            exit();
        }
        $stmt->close();
    }
    // Fermer la connexion à la base de données
    $conn->close();
} else {
    // Si le formulaire n'a pas été soumis, rediriger vers la page précédente
    header("location: ../gestion_salle.php");
    exit();
}
?>
