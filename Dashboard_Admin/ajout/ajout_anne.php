<?php
// Active l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Inclure la connexion à la base de données
include('../../connexion/dbcon.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'ajouter') {
        // Récupérer et sécuriser les données du formulaire
        $annee_debut = isset($_POST['annee_debut']) ? intval($_POST['annee_debut']) : 0;
        $annee_fin = isset($_POST['annee_fin']) ? intval($_POST['annee_fin']) : 0;

        // Valider les données du formulaire
        if ($annee_debut <= 0 || $annee_fin <= 0) {
            header("Location: ../anne.php?error=missing_data");
            exit();
        }

        // Préparer la requête d'insertion
        $sql = "INSERT INTO annees_scolaires (annee_debut, annee_fin) VALUES (?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ii", $annee_debut, $annee_fin);
            if ($stmt->execute()) {
                header("Location: ../anne.php?success=1");
                exit();
            } else {
                header("Location: ../anne.php?error=execution_error");
                exit();
            }
        } else {
            header("Location: ../anne.php?error=prepare_error");
            exit();
        }
    }
     elseif ($action == 'supprimer') {
        // Récupérer et sécuriser les données du formulaire
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        // Valider les données du formulaire
        if ($id <= 0) {
            header("Location: ../anne.php?error=missing_data");
            exit();
        }

        // Préparer la requête de suppression
        $sql = "DELETE FROM annees_scolaires WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                header("Location: ../anne.php?success=1");
                exit();
            } else {
                header("Location: ../anne.php?error=execution_error");
                exit();
            }
        } else {
            header("Location: ../anne.php?error=prepare_error");
            exit();
        }
    } else {
        header("Location: ../anne.php?error=invalid_action");
        exit();
    }
} else {
    header("Location: ../anne.php?error=invalid_request");
    exit();
}
?>
