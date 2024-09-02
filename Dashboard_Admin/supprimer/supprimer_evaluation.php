<?php
// Vérifie si l'ID de l'évaluation à supprimer a été envoyé
if(isset($_POST['id'])) {
    // Récupère l'ID de l'évaluation à supprimer depuis la requête POST
    $evaluation_id = $_POST['id'];

    // Connexion à la base de données
    include('../../connexion/dbcon.php');

    // Prépare la requête SQL pour supprimer l'évaluation avec l'ID spécifié
    $query = "DELETE FROM evaluations WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $evaluation_id);

    // Exécute la requête
    if($stmt->execute()) {
        // Redirection vers la page de gestion des évaluations
        header("Location: ../gestion_evaluation.php");
        exit(); // Assure que le script se termine ici pour éviter toute exécution supplémentaire
    } else {
        // Réponse d'erreur
        echo "Une erreur s'est produite lors de la suppression de l'évaluation.";
    }

    // Ferme la connexion à la base de données
    $stmt->close();
    $conn->close();
} else {
    // Si aucun ID n'est fourni dans la requête POST
    echo "ID d'évaluation non spécifié.";
}
?>
