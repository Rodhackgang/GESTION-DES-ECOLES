<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Inclure la connexion à la base de données
include('../../../connexion/dbcon.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $remplace_id = intval($_POST['remplace-id']);
    $nouveau_staff_id = intval($_POST['nouveau-staff-id']);
    $nouvelle_heure_debut = isset($_POST['nouvelle-heure-debut']) ? $conn->real_escape_string($_POST['nouvelle-heure-debut']) : null;
    $nouvelle_heure_fin = isset($_POST['nouvelle-heure-fin']) ? $conn->real_escape_string($_POST['nouvelle-heure-fin']) : null;

    // Valider les données du formulaire
    if (empty($remplace_id) || empty($nouveau_staff_id)) {
        header("Location: ../gestions_emplois.php?error=missing_data");
        exit();
    }

    // Construire la requête de mise à jour
    $sql = "UPDATE emplois_temps SET staff_id = '$nouveau_staff_id'";
    if (!empty($nouvelle_heure_debut)) {
        $sql .= ", heure_debut = '$nouvelle_heure_debut'";
    }
    if (!empty($nouvelle_heure_fin)) {
        $sql .= ", heure_fin = '$nouvelle_heure_fin'";
    }
    $sql .= " WHERE id = '$remplace_id'";

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
