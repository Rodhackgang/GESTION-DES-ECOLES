<?php
// Inclure la connexion à la base de données
include('../../../connexion/dbcon.php');

// Vérifier si l'ID de l'emploi du temps à supprimer a été reçu
if (isset($_POST['id'])) {
    // Récupérer et sécuriser l'ID de l'emploi du temps à supprimer
    $id = intval($_POST['id']);

    // Préparer la requête de suppression
    $sql = "DELETE FROM emplois_temps WHERE id = ?";

    // Préparer la déclaration SQL et lier les paramètres
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Si la suppression réussit, renvoyer "success"
        echo "success";
    } else {
        // En cas d'erreur, renvoyer un message d'erreur
        echo "Erreur lors de la suppression de l'emploi du temps.";
    }

    // Fermer la déclaration SQL
    $stmt->close();
} else {
    // Si l'ID n'a pas été reçu, renvoyer un message d'erreur
    echo "ID de l'emploi du temps manquant.";
}

// Fermer la connexion à la base de données
$conn->close();
?>
