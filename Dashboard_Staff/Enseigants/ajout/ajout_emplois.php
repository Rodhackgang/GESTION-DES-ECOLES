<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Inclure la connexion à la base de données
include('../../../connexion/dbcon.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $classe_id = intval($_POST['classe-id']);
    $matiere_id = intval($_POST['matiere-id']);
    $staff_id = intval($_POST['staff-id']);
    $jour = $conn->real_escape_string($_POST['jour']);
    $heure_debut = $conn->real_escape_string($_POST['heure-debut']);
    $heure_fin = $conn->real_escape_string($_POST['heure-fin']);

    // Valider les données du formulaire
    if (empty($classe_id) || empty($matiere_id) || empty($staff_id) || empty($jour) || empty($heure_debut) || empty($heure_fin)) {
        header("Location: ../gestions_emplois.php?error=missing_data");
        exit();
    }

    // Préparer la requête d'insertion
    $sql = "INSERT INTO emplois_temps (classe_id, matiere_id, staff_id, jour, heure_debut, heure_fin)
            VALUES ('$classe_id', '$matiere_id', '$staff_id', '$jour', '$heure_debut', '$heure_fin')";

    // Exécuter la requête et vérifier si elle a réussi
    try {
        if ($conn->query($sql) === TRUE) {
            header("Location: ../gestions_emplois.php?success=1");
        } else {
            throw new Exception($conn->error);
        }
    } catch (Exception $e) {
        // Rediriger avec l'erreur
        header("Location: ../gestions_emplois.php?error=" . urlencode($e->getMessage()));
    }

    // Fermer la connexion
    $conn->close();
} else {
    // Si le formulaire n'est pas soumis correctement
    header("Location: ../gestions_emplois.php?error=invalid_request");
    exit();
}
?>
