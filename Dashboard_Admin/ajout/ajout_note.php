<?php
// Afficher toutes les erreurs et avertissements
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once '../../connexion/dbcon.php';

// Fonction pour vérifier si une valeur existe dans une table
function valeur_existe($conn, $table, $colonne, $valeur) {
    $sql = "SELECT COUNT(*) FROM $table WHERE $colonne = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $valeur);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
   return $count > 0;
}

// Vérifier si les données du formulaire sont envoyées en POST
// Vérifier si les données du formulaire sont envoyées en POST
// Vérifier si les données du formulaire sont envoyées en POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $eleve_id = $_POST['eleve-id'];
    $matiere_id = $_POST['matiere-id'];
    $classe_id = $_POST['classe-id']; // Récupérer la valeur de la classe depuis le formulaire
    $semestre = $_POST['semestre'];
    $num_devoir = $_POST['devoir'];
    $note = $_POST['note'];
    $date_evaluation = $_POST['date-evaluation'];
    $commentaire = $_POST['commentaire'];

    // Vérifier si les valeurs des clés étrangères existent
    $eleve_existe = valeur_existe($conn, 'eleves', 'id', $eleve_id);
    $matiere_existe = valeur_existe($conn, 'matieres', 'id', $matiere_id);

    if ($eleve_existe && $matiere_existe) {
        // Préparer la requête SQL pour insérer les données
        $sql = "INSERT INTO notes (eleve_id, matiere_id, note,date_evaluation, commentaire,semestre, num_devoir, classe_id )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Utiliser une requête préparée pour éviter les injections SQL
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iiisdsds", $eleve_id, $matiere_id,$note,$date_evaluation,$commentaire,$semestre,$num_devoir, $classe_id);

            // Exécuter la requête
            if ($stmt->execute()) {
                // Redirection vers la page gestion_note.php après l'insertion réussie
                header("Location: ../gestion_note.php");
                exit(); // Assurez-vous de terminer le script après la redirection
            } else {
                echo "Erreur: " . $stmt->error;
            }

            // Fermer la requête
            $stmt->close();
        } else {
            echo "Erreur de préparation de la requête: " . $conn->error;
        }
    } else {
        if (!$eleve_existe) echo "Erreur: l'ID de l'élève n'existe pas.<br>";
        if (!$matiere_existe) echo "Erreur: l'ID de la matière n'existe pas.<br>";
    }
}



// Fermer la connexion
$conn->close();
?>
