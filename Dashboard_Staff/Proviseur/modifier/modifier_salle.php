<?php
// Inclure le fichier de connexion à la base de données
include_once '../../../connexion/dbcon.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Préparer la requête pour récupérer les données de la salle de classe
    $stmt = $conn->prepare("SELECT * FROM salles_classe WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $salle = $result->fetch_assoc();
        echo json_encode($salle);
    } else {
        echo json_encode(['error' => 'Salle de classe non trouvée']);
    }
} else {
    echo json_encode(['error' => 'ID non fourni']);
}
?>
