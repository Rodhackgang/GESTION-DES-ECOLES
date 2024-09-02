<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifiez si toutes les données nécessaires sont fournies
    $required_fields = ['id', 'eleve-id', 'matiere-id', 'note', 'date-evaluation', 'commentaire', 'semestre', 'devoir', 'classe-id'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field])) {
            header("Location: ../../../Dashboard_Staff/Enseigants/gestion_note.php?error=" . urlencode("Toutes les données nécessaires n'ont pas été fournies"));
            exit();
        }
    }

    include_once '../../../connexion/dbcon.php';

    // Échappez les données pour éviter les injections SQL
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $eleve_id = mysqli_real_escape_string($conn, $_POST['eleve-id']);
    $matiere_id = mysqli_real_escape_string($conn, $_POST['matiere-id']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    $date_evaluation = mysqli_real_escape_string($conn, $_POST['date-evaluation']);
    $commentaire = mysqli_real_escape_string($conn, $_POST['commentaire']);
    $semestre = mysqli_real_escape_string($conn, $_POST['semestre']);
    $num_devoir = mysqli_real_escape_string($conn, $_POST['devoir']);
    $classe_id = mysqli_real_escape_string($conn, $_POST['classe-id']);

    // Préparez la requête SQL en utilisant une requête préparée
    $sql = "UPDATE notes SET 
                eleve_id = ?, 
                matiere_id = ?, 
                note = ?, 
                date_evaluation = ?, 
                commentaire = ?, 
                semestre = ?, 
                num_devoir = ?, 
                classe_id = ?
            WHERE id = ?";

    // Utilisez une requête préparée pour éviter les injections SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisdssii", $eleve_id, $matiere_id, $note, $date_evaluation, $commentaire, $semestre, $num_devoir, $classe_id, $id);

    // Exécutez la requête
    if ($stmt->execute()) {
        // Redirection avec un message de succès
        header("Location: ../../../Dashboard_Staff/Enseigants/gestion_note.php?success=" . urlencode("Les données de la note ont été modifiées avec succès"));
        exit();
    } else {
        // Redirection avec un message d'erreur
        header("Location: ../../../Dashboard_Staff/Enseigants/gestion_note.php?error=" . urlencode("Erreur lors de la modification des données de la note : " . $conn->error));
        exit();
    }

    // Fermez la connexion et la requête préparée
    $stmt->close();
    $conn->close();
} else {
    // Redirection si la requête n'est pas autorisée
    header("Location: ../../../Dashboard_Staff/Enseigants/gestion_note.php?error=" . urlencode("Requête non autorisée"));
    exit();
}
?>
