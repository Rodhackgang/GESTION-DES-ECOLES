<?php
$eleve_id = $_GET['eleve_id'];
$semestre = $_GET['semestre'];

include_once '../../connexion/dbcon.php';

// Récupérer les notes de l'élève pour le semestre sélectionné
$sql = "SELECT note FROM notes WHERE eleve_id = $eleve_id AND semestre = '$semestre'";
$result = $conn->query($sql);

$notes = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
}

echo json_encode($notes);


?>
