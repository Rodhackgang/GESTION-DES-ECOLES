<?php
// Inclure les fichiers nécessaires
include_once '../../../connexion/dbcon.php';

// Vérifier si l'identifiant de l'élève à supprimer a été envoyé
if (isset($_POST['id'])) {
    // Récupérer l'ID de l'élève à supprimer depuis les données envoyées
    $id_eleve = $_POST['id'];

    // Commencer une transaction
    $conn->begin_transaction();

    try {
        // Supprimer les enregistrements associés dans la table bulletins
        $sql_suppression_bulletins = $conn->prepare("DELETE FROM bulletins WHERE eleve_id = ?");
        $sql_suppression_bulletins->bind_param("i", $id_eleve);
        if (!$sql_suppression_bulletins->execute()) {
            throw new Exception("Erreur lors de la suppression des bulletins: " . $sql_suppression_bulletins->error);
        }

        // Supprimer les enregistrements associés dans la table absences
        $sql_suppression_absences = $conn->prepare("DELETE FROM absences WHERE eleve_id = ?");
        $sql_suppression_absences->bind_param("i", $id_eleve);
        if (!$sql_suppression_absences->execute()) {
            throw new Exception("Erreur lors de la suppression des absences: " . $sql_suppression_absences->error);
        }

        // Supprimer les enregistrements associés dans la table notes
        $sql_suppression_notes = $conn->prepare("DELETE FROM notes WHERE eleve_id = ?");
        $sql_suppression_notes->bind_param("i", $id_eleve);
        if (!$sql_suppression_notes->execute()) {
            throw new Exception("Erreur lors de la suppression des notes: " . $sql_suppression_notes->error);
        }

        // Requête SQL pour supprimer l'élève de la base de données
        $sql_suppression_eleve = $conn->prepare("DELETE FROM eleves WHERE id = ?");
        $sql_suppression_eleve->bind_param("i", $id_eleve);
        if (!$sql_suppression_eleve->execute()) {
            throw new Exception("Erreur lors de la suppression de l'élève: " . $sql_suppression_eleve->error);
        }

        // Valider la transaction
        $conn->commit();

        // Redirection vers la page de gestion des élèves avec un message de succès
        header("Location: ../../Dashboard_Staff/Proviseur/gestion_eleves.php?success=deleted");
        exit();
    } catch (Exception $e) {
        // En cas d'échec, annuler la transaction et rediriger avec un message d'erreur
        $conn->rollback();
        header("Location: ../../Dashboard_Staff/Proviseur/gestion_eleves.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si l'identifiant de l'élève n'a pas été envoyé, rediriger avec un message d'erreur
    header("Location: ../../Dashboard_Staff/Proviseur/gestion_eleves.php?error=no_id");
    exit();
}

// Fermer la connexion à la base de données
$conn->close();
?>
