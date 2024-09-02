<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Inclure la connexion à la base de données
include_once '../../connexion/dbcon.php';

// Définir une variable pour le message par défaut
$message = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $expediteur_id = $_POST['expediteur-id'];
    $destinataire_id = $_POST['destinataire_id'];
    $message_content = $_POST['message'];
    $type_expediteur = "Eleve"; // Par défaut, l'expéditeur est considéré comme un élève

    // Préparer la requête d'insertion
    $sql = "INSERT INTO messages (expediteur_id, destinataire_id, message, date_envoi, type_expediteur) 
            VALUES (?, ?, ?, NOW(), ?)";

    // Préparer la déclaration
    $stmt = mysqli_prepare($conn, $sql);

    // Liaison des paramètres
    mysqli_stmt_bind_param($stmt, "iiss", $expediteur_id, $destinataire_id, $message_content, $type_expediteur);

    // Exécuter la déclaration
    if (mysqli_stmt_execute($stmt)) {
        // Le message a été ajouté avec succès
        $message = "Le message a été envoyé avec succès.";
    } else {
        // Une erreur s'est produite lors de l'ajout du message
        $message = "Une erreur s'est produite. Veuillez réessayer.";
    }

    // Fermer la déclaration
    mysqli_stmt_close($stmt);
}

// Redirection vers la page messagerie.php avec le message en paramètre d'URL
header("Location: ../messages.php?message=" . urlencode($message));
exit(); // Assurez-vous d'arrêter l'exécution du script après la redirection
?>
