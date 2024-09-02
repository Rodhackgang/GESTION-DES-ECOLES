<?php
include_once '../../../connexion/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $eleveId = intval($_POST['eleve-id']);
    $semestre = $_POST['semestre'];
    $anneeScolaireId = intval($_POST['annee-scolaire-id']);
    $classeId = intval($_POST['classe-id']); // Nouveau champ pour la classe
    $commentaires = $_POST['commentaires'];

    // Vérification des données reçues
    if (empty($eleveId) || empty($semestre) || empty($anneeScolaireId) || empty($classeId)) {
        die('Tous les champs requis doivent être remplis.');
    }

    // Préparation de la requête SQL
    $query = "INSERT INTO bulletins (eleve_id, semestre, annee_scolaire_id, commentaires,classe_id) VALUES (?, ?, ?, ?, ?)";

    // Préparation de la requête
    if ($stmt = $conn->prepare($query)) {
        // Liaison des paramètres
        $stmt->bind_param('isiss', $eleveId, $semestre, $anneeScolaireId, $commentaires, $classeId);

        // Exécution de la requête
        if ($stmt->execute()) {
            // Redirection vers la page précédente
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Erreur lors de l'ajout du bulletin: " . $stmt->error;
        }

        // Fermeture de la déclaration
        $stmt->close();
    } else {
        echo "Erreur de préparation de la requête: " . $conn->error;
    }

    // Fermeture de la connexion
    $conn->close();
}
?>
