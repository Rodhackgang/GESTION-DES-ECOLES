<?php
// Inclure le fichier de connexion à la base de données
include_once '../../connexion/dbcon.php';

// Définir le chemin de redirection
$redirect = "../gestion_absence.php";

// Vérifier si l'ID de l'absence à supprimer a été envoyé en tant que paramètre POST
if(isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];

    // Préparer et exécuter la requête SQL pour supprimer l'absence
    $sql = "DELETE FROM absences WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // L'absence a été supprimée avec succès
        header("Location: $redirect?success=absence_supprimee");
        exit();
    } else {
        // Erreur lors de la suppression de l'absence
        header("Location: $redirect?error=erreur_suppression_absence");
        exit();
    }
} else {
    // L'ID de l'absence n'a pas été fourni
    header("Location: $redirect?error=id_absence_non_fourni");
    exit();
}
header("Location: $redirect");
?>
