<?php
// Inclure les fichiers nécessaires pour la connexion à la base de données
include_once '../connexion/dbcon.php';

// Fonction pour récupérer les données des élèves par année scolaire
function getChartData($conn) {

    // Tableau pour stocker les données
    $data = array();

    // Requête SQL pour récupérer les données d'évolution par année scolaire
    $sql = "SELECT annee_debut, annee_fin FROM annees_scolaires";
    $result = $conn->query($sql);

    // Traitement des résultats de la requête
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $annee_scolaire = $row['annee_debut'] . '-' . $row['annee_fin'];

            // Requête SQL pour compter le nombre d'élèves par année scolaire
            $sql_eleves = "SELECT COUNT(*) AS total_eleves FROM eleves WHERE annee_scolaire_id IN (SELECT id FROM annees_scolaires WHERE annee_debut = '{$row['annee_debut']}' AND annee_fin = '{$row['annee_fin']}')";
            $result_eleves = $conn->query($sql_eleves);
            $row_eleves = $result_eleves->fetch_assoc();
            $total_eleves = $row_eleves['total_eleves'];

            // Ajouter les données au tableau
            $data['labels'][] = $annee_scolaire;
            $data['datasets'][0]['label'] = 'Élèves';
            $data['datasets'][0]['data'][] = $total_eleves;
        }
    }

    // Retourner les données au format JSON
    return json_encode($data);
}

// Appeler la fonction pour récupérer les données et les afficher en format JSON
echo getChartData($conn);
?>
