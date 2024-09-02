<?php
// Inclure le fichier de connexion à la base de données
include_once '../../connexion/dbcon.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si toutes les données nécessaires sont présentes dans la requête POST
    if (isset($_POST['id'], $_POST['nom'], $_POST['description'], $_POST['enseignant'], $_POST['salle_classe'])) {
        // Échapper les données pour éviter les injections SQL
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $nom = mysqli_real_escape_string($conn, $_POST['nom']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $enseignant = mysqli_real_escape_string($conn, $_POST['enseignant']);
        $salle_classe = mysqli_real_escape_string($conn, $_POST['salle_classe']);

        // Requête pour mettre à jour les données de la matière dans la table matieres
        $sql = "UPDATE matieres SET nom = '$nom', description = '$description', enseignant_id = '$enseignant', classe_id = '$salle_classe' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Modification réussie
            header("Location: ../../Dashboard_Admin/gestion_matiere.php?success=" . urlencode("Les données de la matière ont été modifiées avec succès"));
            exit();
        } else {
            // Erreur lors de la modification
            header("Location: ../../Dashboard_Admin/gestion_matiere.php?error=" . urlencode("Erreur lors de la modification des données de la matière : " . $conn->error));
            exit();
        }
    } else {
        // Données manquantes dans la requête POST
        header("Location: ../../Dashboard_Admin/gestion_matiere.php?error=" . urlencode("Toutes les données nécessaires n'ont pas été fournies"));
        exit();
    }
} else {
    // Requête non autorisée
    header("Location: ../../Dashboard_Admin/gestion_matiere.php?error=" . urlencode("Requête non autorisée"));
    exit();
}
?>
