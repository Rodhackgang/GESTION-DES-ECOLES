<?php
// Afficher toutes les erreurs et avertissements
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once '../../connexion/dbcon.php';

// Vérifier si l'identifiant de la note à supprimer est envoyé en POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $note_id = $_POST['id'];

    // Préparer la requête SQL pour supprimer la note
    $sql = "DELETE FROM notes WHERE id = ?";
    
    // Utiliser une requête préparée pour éviter les injections SQL
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $note_id);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Redirection vers la page gestion_note.php après la suppression réussie
            header("Location: ../gestion_note.php");
            exit(); // Assurez-vous de terminer le script après la redirection
        } else {
            echo "Erreur: " . $stmt->error;
        }

        // Fermer la requête
        $stmt->close();
    } else {
        echo "Erreur de préparation de la requête: " . $conn->error;
    }
} else {
    echo "Erreur: Aucun identifiant de note spécifié ou identifiant invalide.";
}

// Fermer la connexion
$conn->close();
?>
