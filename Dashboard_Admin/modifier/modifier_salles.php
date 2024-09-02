<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si toutes les données nécessaires sont présentes dans la requête POST
    if (isset($_POST['id']) && isset($_POST['nom']) && isset($_POST['capacite']) && isset($_POST['prof_principal'])) {
        // Connexion à la base de données
        include_once './../../connexion/dbcon.php'; // Assurez-vous que le chemin d'accès vers votre fichier de connexion est correct

        // Échapper les données pour éviter les injections SQL
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $nom = mysqli_real_escape_string($conn, $_POST['nom']);
        $capacite = mysqli_real_escape_string($conn, $_POST['capacite']);
        $prof_principal = mysqli_real_escape_string($conn, $_POST['prof_principal']);

        // Requête pour mettre à jour les données de la salle de classe dans la table salles_classe
        $sql = "UPDATE salles_classe SET nom = '$nom', capacite = '$capacite', prof_principal = '$prof_principal' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Modification réussie
            header("Location: ../../Dashboard_Admin/gestion_salle.php?success=" . urlencode("Les données de la salle de classe ont été modifiées avec succès"));
            exit();
        } else {
            // Erreur lors de la modification
            header("Location: ../../Dashboard_Admin/gestion_salle.php?error=" . urlencode("Erreur lors de la modification des données de la salle de classe : " . $conn->error));
            exit();
        }

        // Fermeture de la connexion à la base de données
        $conn->close();
    } else {
        // Données manquantes dans la requête POST
        header("Location: ../../Dashboard_Admin/gestion_salle.php?error=" . urlencode("Toutes les données nécessaires n'ont pas été fournies"));
        exit();
    }
} else {
    // Requête non autorisée
    header("Location: ../../Dashboard_Admin/gestion_salle.php?error=" . urlencode("Requête non autorisée"));
    exit();
}
?>
