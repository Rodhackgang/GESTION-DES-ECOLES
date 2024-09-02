<?php
include_once '../../connexion/dbcon.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $enseignant_id = $_POST['enseignant'];
    $salle_classe_id = $_POST['salle_classe'];

    // Vérifier si le nom de la matière existe déjà
    $check_query = "SELECT * FROM matieres WHERE nom='$nom'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // Le nom de la matière existe déjà, rediriger vers la page de gestion avec un message d'erreur
        header("Location: ../gestion_matiere.php?error=existe_deja");
        exit();
    } else {
        // Le nom de la matière n'existe pas encore, insérer dans la base de données
        $sql = "INSERT INTO matieres (nom, description, enseignant_id, classe_id) VALUES ('$nom', '$description', '$enseignant_id', '$salle_classe_id')";

        if ($conn->query($sql) === TRUE) {
            echo "Nouvelle matière ajoutée avec succès.";
            header("Location: ../gestion_matiere.php");
            exit();
        } else {
            echo "Erreur : " . $sql . "<br>" . $conn->error;
        }
    }
}

?>
