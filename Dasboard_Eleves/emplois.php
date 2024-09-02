<?php
session_start();
include_once '../connexion/dbcon.php';
include_once '../Dasboard_Eleves/liens_utiles/header.php';

// Assurez-vous que l'email est défini, par exemple en le récupérant de la session
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

if ($email) {
    // Préparation de la requête pour obtenir les informations de l'élève
    $query = "SELECT id, nom, prenom, classe_id FROM eleves WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Récupérer les informations de l'élève
        $row = $result->fetch_assoc();
        $eleve_id = $row['id'];
        $classe_id = $row['classe_id'];
    } else {
        die("Aucun élève trouvé avec cet email.");
    }
} else {
    die("Email non défini.");
}
?>

<div class="content-body">
  <div class="container">
  <style>
    .container {
        background-color: #fff;
        border-radius: 20px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 15px auto;
      border-radius: 10px;
    }
    th {
      background-color: blue;
      color: #fff;
      text-align: center;
      padding: 10px 0;
      border: 1px solid blue;
    }
    td {
      border: 1px solid blue;
      padding: 5px;
      text-align: left;
    }
    .heures {
      width: 50px;
      min-width: 50px;
      max-width: 50px;
    }
    .jours {
      width: 150px;
      min-width: 150px;
      max-width: 150px;
      background-color: #f2f2f2;
    }
  </style>

<?php

$sql = "SELECT emplois_temps.jour, emplois_temps.heure_debut, emplois_temps.heure_fin, matieres.nom AS matiere
        FROM emplois_temps
        INNER JOIN matieres ON emplois_temps.matiere_id = matieres.id
        WHERE emplois_temps.classe_id = ?
        ORDER BY emplois_temps.jour, emplois_temps.heure_debut";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $classe_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialisation de la structure des emplois du temps par jour
$emplois_du_temps = [
    'Lundi' => array_fill(8, 9, ''),
    'Mardi' => array_fill(8, 9, ''),
    'Mercredi' => array_fill(8, 9, ''),
    'Jeudi' => array_fill(8, 9, ''),
    'Vendredi' => array_fill(8, 9, ''),
    'Samedi' => array_fill(8, 9, ''),
    'Dimanche' => array_fill(8, 9, '')
];

// Remplissage de la structure des emplois du temps avec les données récupérées
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $jour = $row['jour'];
        $heure_debut = (int)substr($row['heure_debut'], 0, 2);
        $heure_fin = (int)substr($row['heure_fin'], 0, 2);

        for ($i = $heure_debut; $i < $heure_fin; $i++) {
            $emplois_du_temps[$jour][$i] = $row['matiere'];
        }
    }
}

echo '<h1>Emploi du Temps de la Classe ' . $classe_id . '</h1>
      <table>
        <tr>
            <th class="jours">Jour</th>
            <th class="heures">8h - 9h</th>
            <th class="heures">9h - 10h</th>
            <th class="heures">10h - 11h</th>
            <th class="heures">11h - 12h</th>
            <th class="heures">12h - 13h</th>
            <th class="heures">13h - 14h</th>
            <th class="heures">14h - 15h</th>
            <th class="heures">15h - 16h</th>
            <th class="heures">16h - 17h</th>
        </tr>';

foreach ($emplois_du_temps as $jour => $heures) {
    echo '<tr>';
    echo '<td class="jours">' . $jour . '</td>';
    for ($i = 8; $i <= 16; $i++) {
        echo '<td>' . (isset($heures[$i]) ? $heures[$i] : '') . '</td>';
    }
    echo '</tr>';
}

echo '  </table>';
?>

</div>
</div>

<?php
include_once '../Dasboard_Eleves/liens_utiles/footer.php';
?>
