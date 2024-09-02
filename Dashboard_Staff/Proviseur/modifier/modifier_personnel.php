<?php
// Inclure le fichier de connexion à la base de données
include_once '../../connexion/dbcon.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    //echo "ID reçu : " . $id; // Ajoutez cette ligne pour vérifier l'ID reçu

    // Préparer la requête pour récupérer les données du membre du personnel
    $stmt = $conn->prepare("SELECT * FROM staffs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $personnel = $result->fetch_assoc();
        echo json_encode($personnel);
    } else {
        echo json_encode(['error' => 'Membre du personnel non trouvé']);
    }
} else {
    echo json_encode(['error' => 'ID non fourni']);
}
?>
