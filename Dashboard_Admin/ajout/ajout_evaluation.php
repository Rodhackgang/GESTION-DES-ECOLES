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
    $matiere_id = isset($_POST['matiere_id']) ? intval($_POST['matiere_id']) : 0;
    $classe_id = isset($_POST['classe_id']) ? intval($_POST['classe_id']) : 0;
    $titre = isset($_POST['titre']) ? $conn->real_escape_string($_POST['titre']) : '';
    $description = isset($_POST['description']) ? $conn->real_escape_string($_POST['description']) : '';
    $date_evaluation = isset($_POST['date_evaluation']) ? $conn->real_escape_string($_POST['date_evaluation']) : '';
    $annee_scolaire_id = isset($_POST['annee_scolaire_id']) ? intval($_POST['annee_scolaire_id']) : 0;

    // Valider les données du formulaire
    if ($matiere_id <= 0 || $classe_id <= 0 || empty($titre) || empty($description) || empty($date_evaluation) || $annee_scolaire_id <= 0) {
        // Rediriger vers la page précédente si les données sont invalides
        header("Location: " . $_SERVER["HTTP_REFERER"] . "?error=missing_data");
        exit();
    }

    // Préparer la requête d'insertion
    $sql = "INSERT INTO evaluations (matiere_id, classe_id, titre, description, date_evaluation, annee_scolaire_id) VALUES (?, ?, ?, ?, ?, ?)";

    // Préparer et exécuter la requête avec des déclarations paramétrées
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iisssi", $matiere_id, $classe_id, $titre, $description, $date_evaluation, $annee_scolaire_id);
        if ($stmt->execute()) {
            // Rediriger vers la page de gestion des évaluations avec succès
            header("Location: " . $_SERVER["HTTP_REFERER"] . "?success=1");
            exit();
        } else {
            // Rediriger vers la page de gestion des évaluations en cas d'erreur d'exécution
            header("Location: " . $_SERVER["HTTP_REFERER"] . "?error=execution_error");
            exit();
        }
    } else {
        // Rediriger vers la page de gestion des évaluations en cas d'erreur de préparation de requête
        header("Location: " . $_SERVER["HTTP_REFERER"] . "?error=prepare_error");
        exit();
    }
} else {
    // Si le formulaire n'est pas soumis correctement, redirige vers la page précédente avec une erreur
    header("Location: " . $_SERVER["HTTP_REFERER"] . "?error=invalid_request");
    exit();
}
?>
