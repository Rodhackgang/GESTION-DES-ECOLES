<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer l'ID du personnel à supprimer
    $id = $_POST['id'];

    include_once '../../connexion/dbcon.php';
    // Préparer et exécuter la requête de suppression
    $stmt = $conn->prepare("DELETE FROM staffs WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "Personnel supprimé avec succès.";
        header('Location: ../gestion_personnel.php');
    exit;
    } else {
        echo "Erreur lors de la suppression du personnel : " . $conn->error;
        header('Location: ../gestion_personnel.php');
    exit;
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();

    // Redirection après suppression
    header('Location: ../gestion_personnel.php');
    exit;
} else {
    // Si la requête n'est pas POST, rediriger vers la liste du personnel
    header('Location: ../gestion_personnel.php');
    exit;
}
?>
