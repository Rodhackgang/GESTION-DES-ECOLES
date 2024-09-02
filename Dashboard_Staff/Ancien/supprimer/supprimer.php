<?php
// Inclure les fichiers nécessaires
include_once '../../connexion/dbcon.php';

// Vérifier si l'identifiant de l'élève à supprimer a été envoyé
if (isset($_POST['id'])) {
    // Récupérer l'ID de l'élève à supprimer depuis les données envoyées
    $id_eleve = $_POST['id'];

    // Supprimer les enregistrements associés dans la table absences
    $sql_suppression_absences = "DELETE FROM absences WHERE eleve_id = '$id_eleve'";
    if ($conn->query($sql_suppression_absences) === FALSE) {
        // En cas d'échec, rediriger avec un message d'erreur
        header("Location: ../../Dashboard_Staff/staff_dashboard.php?error=delete_absences_failed");
        exit();
    }

    // Supprimer les enregistrements associés dans la table notes
    $sql_suppression_notes = "DELETE FROM notes WHERE eleve_id = '$id_eleve'";
    if ($conn->query($sql_suppression_notes) === FALSE) {
        // En cas d'échec, rediriger avec un message d'erreur
        header("Location: ../../Dashboard_Staff/staff_dashboard.php?error=delete_notes_failed");
        exit();
    }

    // Requête SQL pour supprimer l'élève de la base de données
    $sql_suppression_eleve = "DELETE FROM eleves WHERE id = '$id_eleve'";
    if ($conn->query($sql_suppression_eleve) === TRUE) {
        // Redirection vers la page de gestion des élèves avec un message de succès
        header("Location: ../../Dashboard_Staff/staff_dashboard.php?success=deleted");
        exit();
    } else {
        // En cas d'échec, rediriger avec un message d'erreur
        header("Location: ../../Dashboard_Staff/staff_dashboard.php?error=delete_failed");
        exit();
    }
} else {
    // Si l'identifiant de l'élève n'a pas été envoyé, rediriger avec un message d'erreur
    header("Location: ../../Dashboard_Staff/staff_dashboard.php?error=no_id");
    exit();
}

// Fermer la connexion à la base de données
$conn->close();
?>
