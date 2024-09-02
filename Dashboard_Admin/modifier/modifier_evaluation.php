<?php
// Vérifier si l'ID de l'évaluation est présent dans la requête GET
if (isset($_GET['id'])) {
    // Inclure le fichier de connexion à la base de données
    include_once '../../connexion/dbcon.php'; // Assurez-vous que le chemin d'accès vers votre fichier de connexion est correct

    // Échapper les données pour éviter les injections SQL
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Requête pour récupérer les données de l'évaluation avec l'ID spécifié
    $sql = "SELECT id, matiere_id, titre, description, date_evaluation FROM evaluations WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Récupérer les données de l'évaluation sous forme de tableau associatif
        $row = $result->fetch_assoc();

        // Convertir le tableau associatif en format JSON et l'afficher
        echo json_encode($row);
    } else {
        // Aucune évaluation trouvée avec l'ID spécifié
        echo json_encode(array("error" => "Aucune évaluation trouvée avec l'ID spécifié"));
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    // ID de l'évaluation non fourni dans la requête GET
    echo json_encode(array("error" => "ID de l'évaluation non spécifié"));
}
?>
