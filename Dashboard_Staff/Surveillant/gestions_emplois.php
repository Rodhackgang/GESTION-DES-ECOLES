<?php
include_once './liens_utiles/header.php';
include_once '../../connexion/dbcon.php';
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
    /* Style général du tableau */
    table {
      width: 100%; /* Prendre toute la largeur disponible */
      border-collapse: collapse; /* Supprimer les bordures entre les cellules */
      margin: 15px auto; /* Centrer le tableau horizontalement */
      margin-left: 10px;
      border-radius: 10px;
    }

    /* Style des en-têtes de colonne */
    th {
      background-color: blue; /* Couleur de fond gris foncé */
      color: #fff; /* Couleur de texte blanche */
      text-align: center; /* Centrer le texte horizontalement */
      padding: 10px 0; /* Marge interne pour l'espacement */
      border: 1px solid blue; /* Bordure grise */
      padding: 10;

    }

    /* Style des cellules de données */
    td {
      border: 1px solid blue; /* Bordure grise */
      padding: 5px; /* Marge interne pour l'espacement */
      text-align: left; /* Aligner le texte à gauche */
    }

    /* Style des cellules pour les heures */
    .heures {
      width: 50px; /* Largeur fixe pour les cellules d'heures */
      min-width: 50px; /* Largeur minimale pour les cellules d'heures */
      max-width: 50px; /* Largeur maximale pour les cellules d'heures */
    }

    /* Style des cellules pour les jours */
    .jours {
      width: 150px; /* Largeur fixe pour les cellules de jours */
      min-width: 150px; /* Largeur minimale pour les cellules de jours */
      max-width: 150px; /* Largeur maximale pour les cellules de jours */
      background-color: #f2f2f2; /* Couleur de fond gris clair */
    }
  </style>

<?php

$sql = "SELECT emplois_temps.jour, emplois_temps.heure_debut, emplois_temps.heure_fin, matieres.nom AS matiere, salles_classe.nom AS classe
        FROM emplois_temps
        INNER JOIN matieres ON emplois_temps.matiere_id = matieres.id
        INNER JOIN salles_classe ON emplois_temps.classe_id = salles_classe.id
        ORDER BY salles_classe.nom, emplois_temps.jour, emplois_temps.heure_debut";

$result = $conn->query($sql);

// Initialisation de la structure des emplois du temps par classe
$emplois_du_temps_par_classe = [];

// Remplissage de la structure des emplois du temps par classe avec les données récupérées
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $classe = $row['classe'];
        $jour = $row['jour'];
        $heure_debut = (int)substr($row['heure_debut'], 0, 2);
        $heure_fin = (int)substr($row['heure_fin'], 0, 2);

        if (!isset($emplois_du_temps_par_classe[$classe])) {
            $emplois_du_temps_par_classe[$classe] = [
                'Lundi' => array_fill(8, 9, ''),
                'Mardi' => array_fill(8, 9, ''),
                'Mercredi' => array_fill(8, 9, ''),
                'Jeudi' => array_fill(8, 9, ''),
                'Vendredi' => array_fill(8, 9, ''),
                'Samedi' => array_fill(8, 9, ''),
                'Dimanche' => array_fill(8, 9, '')
            ];
        }

        for ($i = $heure_debut; $i < $heure_fin; $i++) {
            $emplois_du_temps_par_classe[$classe][$jour][$i] = $row['matiere'];
        }
    }
}



foreach ($emplois_du_temps_par_classe as $classe => $emplois_du_temps) {
    echo '<h1>Emploi du Temps de la Classe ' . $classe . '</h1>
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
}



?>


</div>
</div>
<?php
include_once './liens_utiles/footer.php';
?>