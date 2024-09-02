<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si toutes les données nécessaires sont présentes dans la requête POST
    if (isset($_POST['id']) && isset($_POST['eleve_id']) && isset($_POST['date_absence']) && isset($_POST['motif'])) {
        // Connexion à la base de données
        include_once './../../connexion/dbcon.php'; // Assurez-vous que le chemin d'accès vers votre fichier de connexion est correct

        // Échapper les données pour éviter les injections SQL
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $eleve_id = mysqli_real_escape_string($conn, $_POST['eleve_id']);
        $date_absence = mysqli_real_escape_string($conn, $_POST['date_absence']);
        $motif = mysqli_real_escape_string($conn, $_POST['motif']);

        // Requête pour mettre à jour les données de l'absence dans la table absences
        $sql = "UPDATE absences SET eleve_id = '$eleve_id', date_absence = '$date_absence', motif = '$motif' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Modification réussie
            echo json_encode(["success" => "Les données de l'absence ont été modifiées avec succès"]);
            header('Location: ../suivis_abscence.php');
        } else {
            // Erreur lors de la modification
            echo json_encode(["error" => "Erreur lors de la modification des données de l'absence : " . $conn->error]);
        }

        // Fermeture de la connexion à la base de données
        $conn->close();
    } else {
        // Données manquantes dans la requête POST
        echo json_encode(["error" => "Toutes les données nécessaires n'ont pas été fournies"]);
    }
} else {
    // Requête non autorisée
    echo json_encode(["error" => "Requête non autorisée"]);
}
?>
