<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si toutes les données nécessaires sont présentes dans la requête POST
    if (isset($_POST['id']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['date_naissance']) && isset($_POST['adresse']) && isset($_POST['telephone']) && isset($_POST['email']) && isset($_POST['date_inscription']) && isset($_POST['annee_scolaire']) && isset($_POST['classe'])) {
        // Connexion à la base de données
        include_once '../../../connexion/dbcon.php'; // Assurez-vous que le chemin d'accès vers votre fichier de connexion est correct

        // Échapper les données pour éviter les injections SQL
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $nom = mysqli_real_escape_string($conn, $_POST['nom']);
        $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
        $date_naissance = mysqli_real_escape_string($conn, $_POST['date_naissance']);
        $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
        $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $date_inscription = mysqli_real_escape_string($conn, $_POST['date_inscription']);
        $annee_scolaire = mysqli_real_escape_string($conn, $_POST['annee_scolaire']);
        $classe = mysqli_real_escape_string($conn, $_POST['classe']);

        // Requête pour mettre à jour les données de l'élève dans la table eleves
        $sql = "UPDATE eleves SET nom = '$nom', prenom = '$prenom', date_naissance = '$date_naissance', adresse = '$adresse', telephone = '$telephone', email = '$email', date_inscription = '$date_inscription', annee_scolaire_id = '$annee_scolaire', classe_id = '$classe' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Modification réussie
            header("Location: ../../Dashboard_Admin/gestion_eleves.php?success=" . urlencode("Les données de l'élève ont été modifiées avec succès"));
            exit();
        } else {
            // Erreur lors de la modification
            header("Location: ../../Dashboard_Admin/gestion_eleves.php?error=" . urlencode("Erreur lors de la modification des données de l'élève : " . $conn->error));
            exit();
        }

        // Fermeture de la connexion à la base de données
        $conn->close();
    } else {
        // Données manquantes dans la requête POST
        header("Location: ../../Dashboard_Admin/gestion_eleves.php?error=" . urlencode("Toutes les données nécessaires n'ont pas été fournies"));
        exit();
    }
} else {
    // Requête non autorisée
    header("Location: ../../Dashboard_Admin/gestion_eleves.php?error=" . urlencode("Requête non autorisée"));
    exit();
}
?>
