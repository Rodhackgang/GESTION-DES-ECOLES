<?php
// Vérifier si l'ID de l'attribution est présent dans la requête GET
if (isset($_GET['id'])) {
    // Connexion à la base de données
    include_once '../../connexion/dbcon.php'; // Assurez-vous que le chemin d'accès vers votre fichier de connexion est correct

    // Échapper les données pour éviter les injections SQL
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Requête pour récupérer les données de l'attribution avec l'ID spécifié
    $sql = "SELECT id, cours, salle, date, heure_debut, heure_fin FROM attribution_salles WHERE id = ?";
    
    // Préparer et exécuter la requête avec l'ID fourni
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Récupérer les données de l'attribution sous forme de tableau associatif
            $row = $result->fetch_assoc();

            // Convertir le tableau associatif en format JSON et l'afficher
            echo json_encode($row);
        } else {
            // Aucune attribution trouvée avec l'ID spécifié
            echo json_encode(array("error" => "Aucune attribution trouvée avec l'ID spécifié"));
        }
        
        $stmt->close();
    } else {
        // Erreur lors de la préparation de la requête
        echo json_encode(array("error" => "Erreur lors de la préparation de la requête : " . $conn->error));
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    // ID de l'attribution non fourni dans la requête GET
    echo json_encode(array("error" => "ID de l'attribution non spécifié"));
}
?>
