<?php
// Activer les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure la connexion à la base de données
include_once '../../../connexion/dbcon.php';

// Vérifier si l'ID de l'évaluation est passé
if (isset($_POST['id'])) {
    $evaluation_id = $_POST['id'];

    // Préparer la requête de suppression
    $query = "DELETE FROM evaluations WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $evaluation_id);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "Évaluation supprimée avec succès.";
    } else {
        echo "Erreur lors de la suppression de l'évaluation : " . $stmt->error;
    }

    // Fermer la déclaration et la connexion
    $stmt->close();
    $conn->close();
} else {
    echo "ID de l'évaluation manquant.";
}
?>
