<?php
// Vérifier si l'ID de l'élève est présent dans la requête GET
if (isset($_GET['id'])) {
    // Connexion à la base de données
    include_once './../../connexion/dbcon.php'; // Assurez-vous que le chemin d'accès vers votre fichier de connexion est correct

    // Échapper les données pour éviter les injections SQL
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Requête pour récupérer les données de l'élève avec l'ID spécifié
    $sql = "SELECT id, nom, prenom, date_naissance, adresse, telephone, email, date_inscription, annee_scolaire_id, classe_id FROM eleves WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Récupérer les données de l'élève sous forme de tableau associatif
        $row = $result->fetch_assoc();

        // Convertir le tableau associatif en format JSON et l'afficher
        echo json_encode($row);
    } else {
        // Aucun élève trouvé avec l'ID spécifié
        echo json_encode(array("error" => "Aucun élève trouvé avec l'ID spécifié"));
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    // ID de l'élève non fourni dans la requête GET
    echo json_encode(array("error" => "ID de l'élève non spécifié"));
}
?>
