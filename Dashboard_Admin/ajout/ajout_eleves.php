<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// Paramètres de connexion à la base de données
include_once '../../connexion/dbcon.php';

// Récupération des données du formulaire
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$date_naissance = $_POST['date_naissance'];
$adresse = $_POST['adresse'];
$telephone = $_POST['telephone'];
$email = $_POST['email'];
$date_inscription = $_POST['date_inscription'];
$annee_scolaire = $_POST['annee_scolaire']; // Ajout de la récupération de l'année scolaire
$classe = $_POST['classe']; // Ajout de la récupération de la classe

// Vérification si l'email existe déjà
$sql_check_email = "SELECT id FROM eleves WHERE email = '$email'";
$result = $conn->query($sql_check_email);

if ($result->num_rows > 0) {
    // Si l'email existe déjà, rediriger avec un message d'erreur
    header("Location: ../../Dashboard_Admin/gestion_eleves.php?error=email_exists");
    exit();
} else {
    // Si l'email n'existe pas, procéder à l'insertion
    $sql_insert = "INSERT INTO eleves (nom, prenom, date_naissance, adresse, telephone, email, date_inscription, annee_scolaire_id, classe_id)
                   VALUES ('$nom', '$prenom', '$date_naissance', '$adresse', '$telephone', '$email', '$date_inscription', '$annee_scolaire', '$classe')";

    if ($conn->query($sql_insert) === TRUE) {
        // Redirection vers la page de gestion des élèves
        header("Location: ../../Dashboard_Admin/gestion_eleves.php?success=inserted");
        exit();
    } else {
        // Si l'insertion échoue, rediriger avec un message d'erreur
        header("Location: ../../Dashboard_Admin/gestion_eleves.php?error=insert_failed");
        exit();
    }
}

// Fermeture de la connexion
$conn->close();
?>
