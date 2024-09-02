<?php
// Active l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Inclure la connexion à la base de données
include('../../connexion/dbcon.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $evaluation_id = intval($_POST['id']);
    $nouveau_matiere_id = intval($_POST['matiere_id']);
    $nouveau_classe_id = intval($_POST['classe_id']);
    $nouveau_titre = isset($_POST['titre']) ? $conn->real_escape_string($_POST['titre']) : null;
    $nouvelle_description = isset($_POST['description']) ? $conn->real_escape_string($_POST['description']) : null;
    $nouvelle_date_evaluation = isset($_POST['date_evaluation']) ? $conn->real_escape_string($_POST['date_evaluation']) : null;
    $nouvelle_annee_scolaire_id = intval($_POST['annee_scolaire_id']);

    // Valider les données du formulaire
    if (empty($evaluation_id) || empty($nouveau_matiere_id) || empty($nouveau_classe_id) || empty($nouveau_titre) || empty($nouvelle_description) || empty($nouvelle_date_evaluation) || empty($nouvelle_annee_scolaire_id)) {
        header("Location: ../gestion_evaluation.php?error=missing_data");
        exit();
    }

    // Préparer la requête de mise à jour
    $sql = "UPDATE evaluations SET matiere_id = ?, classe_id = ?, titre = ?, description = ?, date_evaluation = ?, annee_scolaire_id = ? WHERE id = ?";

    // Exécuter la requête avec des déclarations paramétrées
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iisssii", $nouveau_matiere_id, $nouveau_classe_id, $nouveau_titre, $nouvelle_description, $nouvelle_date_evaluation, $nouvelle_annee_scolaire_id, $evaluation_id);
        if ($stmt->execute()) {
            // Rediriger en cas de succès
            header("Location: ../gestion_evaluation.php?success=1");
        } else {
            // Rediriger avec l'erreur d'exécution
            header("Location: ../gestion_evaluation.php?error=execution_error");
        }
        $stmt->close();
    } else {
        // Rediriger avec l'erreur de préparation de la requête
        header("Location: ../gestion_evaluation.php?error=prepare_error");
    }

    // Fermer la connexion
    $conn->close();
} else {
    // Si le formulaire n'est pas soumis correctement
    header("Location: ../gestion_evaluation.php?error=invalid_request");
    exit();
}
?>
