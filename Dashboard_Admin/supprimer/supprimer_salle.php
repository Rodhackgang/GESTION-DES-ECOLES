<?php
// Inclure le fichier de connexion à la base de données
include_once '../../../connexion/dbcon.php';

$redirect = "../../../Dashboard_Staff/Enseigants/gestion_salle.php";

// Vérifier si l'ID de la salle à supprimer a été envoyé en tant que paramètre POST
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];

    // Commencer une transaction
    $conn->begin_transaction();

    try {
        // Supprimer les enregistrements associés dans la table emplois_temps
        $sql_suppression_emplois_temps = $conn->prepare("DELETE FROM emplois_temps WHERE classe_id = ?");
        $sql_suppression_emplois_temps->bind_param("i", $id);
        if (!$sql_suppression_emplois_temps->execute()) {
            throw new Exception("Erreur lors de la suppression des emplois du temps: " . $sql_suppression_emplois_temps->error);
        }

        // Supprimer les enregistrements associés dans la table evaluations
        $sql_suppression_evaluations = $conn->prepare("DELETE FROM evaluations WHERE classe_id = ?");
        $sql_suppression_evaluations->bind_param("i", $id);
        if (!$sql_suppression_evaluations->execute()) {
            throw new Exception("Erreur lors de la suppression des évaluations: " . $sql_suppression_evaluations->error);
        }

        // Supprimer les enregistrements associés dans la table matieres
        $sql_suppression_matieres = $conn->prepare("DELETE FROM matieres WHERE classe_id = ?");
        $sql_suppression_matieres->bind_param("i", $id);
        if (!$sql_suppression_matieres->execute()) {
            throw new Exception("Erreur lors de la suppression des matières: " . $sql_suppression_matieres->error);
        }

        // Supprimer les enregistrements associés dans la table notes
        $sql_suppression_notes = $conn->prepare("DELETE FROM notes WHERE classe_id = ?");
        $sql_suppression_notes->bind_param("i", $id);
        if (!$sql_suppression_notes->execute()) {
            throw new Exception("Erreur lors de la suppression des notes: " . $sql_suppression_notes->error);
        }

        // Supprimer les enregistrements associés dans la table bulletins
        $sql_suppression_bulletins = $conn->prepare("DELETE FROM bulletins WHERE classe_id = ?");
        $sql_suppression_bulletins->bind_param("i", $id);
        if (!$sql_suppression_bulletins->execute()) {
            throw new Exception("Erreur lors de la suppression des bulletins: " . $sql_suppression_bulletins->error);
        }

        // Supprimer les enregistrements associés dans la table staffs
        $sql_suppression_staffs = $conn->prepare("UPDATE staffs SET classe_id = NULL WHERE classe_id = ?");
        $sql_suppression_staffs->bind_param("i", $id);
        if (!$sql_suppression_staffs->execute()) {
            throw new Exception("Erreur lors de la dissociation des staffs: " . $sql_suppression_staffs->error);
        }

        // Supprimer les enregistrements associés dans la table eleves
        $sql_suppression_eleves = $conn->prepare("UPDATE eleves SET classe_id = NULL WHERE classe_id = ?");
        $sql_suppression_eleves->bind_param("i", $id);
        if (!$sql_suppression_eleves->execute()) {
            throw new Exception("Erreur lors de la dissociation des élèves: " . $sql_suppression_eleves->error);
        }

        // Supprimer la salle de classe
        $sql_suppression_salle = $conn->prepare("DELETE FROM salles_classe WHERE id = ?");
        $sql_suppression_salle->bind_param("i", $id);
        if (!$sql_suppression_salle->execute()) {
            throw new Exception("Erreur lors de la suppression de la salle de classe: " . $sql_suppression_salle->error);
        }

        // Valider la transaction
        $conn->commit();

        // Redirection vers la page de gestion des salles avec un message de succès
        header("Location: $redirect?success=salle_supprimee");
        exit();
    } catch (Exception $e) {
        // En cas d'échec, annuler la transaction et rediriger avec un message d'erreur
        $conn->rollback();
        header("Location: $redirect?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si l'identifiant de la salle n'a pas été envoyé, rediriger avec un message d'erreur
    header("Location: $redirect?error=id_salle_non_fourni");
    exit();
}

// Fermer la connexion à la base de données
$conn->close();
?>
