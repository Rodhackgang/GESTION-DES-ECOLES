<?php
// Vérifier si l'ID de la matière est présent dans la requête GET
if (isset($_GET['id'])) {
    // Connexion à la base de données
    include_once '../../../connexion/dbcon.php'; // Assure-toi que le chemin d'accès vers ton fichier de connexion est correct

    // Échapper les données pour éviter les injections SQL
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Requête pour récupérer les données de la matière avec l'ID spécifié
    $sql = "SELECT matieres.id, matieres.nom, matieres.description 
            FROM matieres 
            INNER JOIN notes ON matieres.id = notes.matiere_id 
            WHERE notes.id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Récupérer les données de la matière sous forme de tableau associatif
        $row = $result->fetch_assoc();

        // Convertir le tableau associatif en format JSON et l'afficher
        echo json_encode($row);
    } else {
        // Aucune matière trouvée avec l'ID spécifié
        echo json_encode(array("error" => "Aucune matière trouvée avec l'ID spécifié"));
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    // ID de la matière non fourni dans la requête GET
    echo json_encode(array("error" => "ID de la matière non spécifié"));
}
?>
