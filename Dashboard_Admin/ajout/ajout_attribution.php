<?php
// Inclure le fichier de configuration de la base de données
include_once "../../connexion/dbcon.php";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $cours = $_POST['cours'];
    $salle = $_POST['salle'];
    $date = $_POST['date'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];

    // Préparer la requête d'insertion
    $query = "INSERT INTO attribution_salles (cours, salle, date, heure_debut, heure_fin) VALUES (?, ?, ?, ?, ?)";

    // Préparer et exécuter la requête avec les valeurs fournies
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssss", $cours, $salle, $date, $heure_debut, $heure_fin);
        if ($stmt->execute()) {
            // Si l'insertion est réussie, rediriger vers une page de confirmation
            header("Location: ../gestion_attribution.php");
            exit();
        } else {
            // En cas d'erreur lors de l'insertion, afficher un message d'erreur
            echo "Une erreur s'est produite lors de l'ajout de l'attribution de salle.";
        }
        $stmt->close();
    }
}

// Fermer la connexion à la base de données

?>
