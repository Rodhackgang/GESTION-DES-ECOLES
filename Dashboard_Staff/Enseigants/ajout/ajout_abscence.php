<?php
include_once '../../connexion/dbcon.php';

// Récupérer les données du formulaire
$eleve_id = $_POST['eleve-id'];
$date_absence = $_POST['date-absence'];
$motif = $_POST['motif'];

// Vérifier si l'ID de l'élève existe
$check_sql = "SELECT id FROM eleves WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $eleve_id);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    // Préparer la requête SQL
    $sql = "INSERT INTO absences (eleve_id, date_absence, motif) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $eleve_id, $date_absence, $motif);

    if ($stmt->execute()) {
        // Rediriger en cas de succès
        header("Location: ../suivis_abscence.php?message=success");
    } else {
        // Rediriger en cas d'erreur d'insertion
        header("Location: ../suivis_abscence.php?message=error&error=" . urlencode($stmt->error));
    }

    // Fermer la connexion
    $stmt->close();
} else {
    // Rediriger si l'ID de l'élève n'existe pas
    header("Location: ../suivis_abscence.php?message=error&error=" . urlencode("ID de l'élève n'existe pas"));
}

$check_stmt->close();
$conn->close();
exit();
?>
