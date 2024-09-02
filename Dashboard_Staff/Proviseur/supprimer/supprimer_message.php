<?php
session_start();
// Inclure le fichier de connexion à la base de données
include_once '../../../connexion/dbcon.php';

// Vérifier si le message_id a été envoyé
if(isset($_POST['message_id'])) {
    // Récupérer le message_id
    $message_id = $_POST['message_id'];

    // Requête SQL pour supprimer le message de la base de données
    $sql = "DELETE FROM messages WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $message_id);

    // Exécuter la requête
    if(mysqli_stmt_execute($stmt)) {
        // Redirection vers la page précédente avec un message de succès
        header("Location: ../messagerie.php?message=succès : message supprimé avec succès");
        exit();
    } else {
        // Redirection vers la page précédente avec un message d'erreur
        header("Location: ../messagerie.php?message=erreur : échec de la suppression du message");
        exit();
    }
} else {
    // Redirection vers la page précédente avec un message d'erreur
    header("Location: ../messagerie.php?message=erreur : aucun message ID spécifié");
    exit();
}
?>
