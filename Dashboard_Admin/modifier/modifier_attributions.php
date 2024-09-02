<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si toutes les données nécessaires sont présentes dans la requête POST
    if (isset($_POST['id']) && isset($_POST['cours']) && isset($_POST['salle']) && isset($_POST['date']) && isset($_POST['heure_debut']) && isset($_POST['heure_fin'])) {
        // Connexion à la base de données
        include_once '../../connexion/dbcon.php'; // Assurez-vous que le chemin d'accès vers votre fichier de connexion est correct

        // Échapper les données pour éviter les injections SQL
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $cours = mysqli_real_escape_string($conn, $_POST['cours']);
        $salle = mysqli_real_escape_string($conn, $_POST['salle']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $heure_debut = mysqli_real_escape_string($conn, $_POST['heure_debut']);
        $heure_fin = mysqli_real_escape_string($conn, $_POST['heure_fin']);

        // Requête pour mettre à jour les données de l'attribution dans la table attribution_salles
        $sql = "UPDATE attribution_salles SET cours = ?, salle = ?, date = ?, heure_debut = ?, heure_fin = ? WHERE id = ?";

        // Préparer et exécuter la requête avec les valeurs fournies
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssi", $cours, $salle, $date, $heure_debut, $heure_fin, $id);
            if ($stmt->execute()) {
                // Modification réussie
                header("Location: ../../Dashboard_Admin/gestion_attribution.php?success=" . urlencode("Les données de l'attribution ont été modifiées avec succès"));
                exit();
            } else {
                // Erreur lors de la modification
                header("Location: ../../Dashboard_Admin/gestion_attribution.php?error=" . urlencode("Erreur lors de la modification des données de l'attribution : " . $stmt->error));
                exit();
            }
            $stmt->close();
        } else {
            // Erreur lors de la préparation de la requête
            header("Location: ../../Dashboard_Admin/gestion_attribution.php?error=" . urlencode("Erreur lors de la préparation de la requête : " . $conn->error));
            exit();
        }

        // Fermeture de la connexion à la base de données
        $conn->close();
    } else {
        // Données manquantes dans la requête POST
        header("Location: ../../Dashboard_Admin/gestion_attribution.php?error=" . urlencode("Toutes les données nécessaires n'ont pas été fournies"));
        exit();
    }
} else {
    // Requête non autorisée
    header("Location: ../../Dashboard_Admin/gestion_attribution.php?error=" . urlencode("Requête non autorisée"));
    exit();
}
?>
