<?php
include_once '../connexion/dbcon.php';

$sql = "SELECT id, nom FROM salles_classe";
$result = $conn->query($sql);

$classes = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $classes[] = array("id" => $row['id'], "nom" => $row['nom']);
    }
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($classes);
?>
