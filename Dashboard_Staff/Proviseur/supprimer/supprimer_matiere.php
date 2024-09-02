<?php
// Inclure le fichier de connexion à la base de données
include_once '../../../connexion/dbcon.php';

// Définir le chemin de redirection
$redirect = "../gestion_matiere.php";

// Vérifier si l'ID de la matière à supprimer a été envoyé en tant que paramètre POST
if(isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];

    // Supprimer d'abord les enregistrements correspondants dans la table des évaluations
    $delete_evaluations_query = "DELETE FROM evaluations WHERE matiere_id = $id";
    if ($conn->query($delete_evaluations_query) === TRUE) {
        // Les enregistrements correspondants dans la table des évaluations ont été supprimés avec succès

        // Supprimer ensuite les enregistrements correspondants dans la table des notes
        $delete_notes_query = "DELETE FROM notes WHERE matiere_id = $id";
        if ($conn->query($delete_notes_query) === TRUE) {
            // Les enregistrements correspondants dans la table des notes ont été supprimés avec succès

            // Préparer et exécuter la requête SQL pour supprimer la matière
            $sql = "DELETE FROM matieres WHERE id = $id";

            if ($conn->query($sql) === TRUE) {
                // La matière a été supprimée avec succès
                header("Location: $redirect?success=matiere_supprimee");
                exit();
            } else {
                // Erreur lors de la suppression de la matière
                header("Location: $redirect?error=erreur_suppression_matiere");
                exit();
            }
        } else {
            // Erreur lors de la suppression des enregistrements correspondants dans la table des notes
            header("Location: $redirect?error=erreur_suppression_notes");
            exit();
        }
    } else {
        // Erreur lors de la suppression des enregistrements correspondants dans la table des évaluations
        header("Location: $redirect?error=erreur_suppression_evaluations");
        exit();
    }
} else {
    // L'ID de la matière n'a pas été fourni
    header("Location: $redirect?error=id_matiere_non_fourni");
    exit();
}
?>
