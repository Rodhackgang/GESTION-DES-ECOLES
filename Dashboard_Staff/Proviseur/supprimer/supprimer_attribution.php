<?php
// Inclure le fichier de configuration de la base de données
include_once "../../../connexion/dbcon.php";

// Vérifier si l'ID de l'attribution à supprimer a été envoyé en tant que paramètre POST
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];

    // Préparer et exécuter la requête SQL pour supprimer l'attribution
    $sql = "DELETE FROM attribution_salles WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Rediriger vers la page de gestion des salles avec un message de succès
            echo "success";
        } else {
            // En cas d'erreur lors de la suppression, envoyer une réponse d'erreur
            echo "error";
        }
        $stmt->close();
    }
} else {
    // En cas d'erreur lors de la suppression, envoyer une réponse d'erreur
    echo "error";
}

// Fermer la connexion à la base de données

?>
