<?php
include_once '../../connexion/dbcon.php';
//Récupération des données du formulaire
$salle = $_POST['salle-entretien'];
$date_entretien = $_POST['date-entretien'];
$description = $_POST['description-entretien'];

// Requête d'insertion
$sql = "INSERT INTO Entretien (salle, date_entretien, description) VALUES ('$salle', '$date_entretien', '$description')";

if ($conn->query($sql) === TRUE) {
    echo "Entretien planifié avec succès";
} else {
    echo "Erreur lors de la planification de l'entretien: " . $conn->error;
}

// Fermeture de la connexion
$conn->close();
?>
