<?php
include_once '../Dashboard_Staff/liens_utiles/header.php';
include_once '../connexion/dbcon.php';

$sql = "SELECT eleves.nom AS nom_eleve, eleves.prenom AS prenom_eleve, absences.date_absence, absences.motif
        FROM absences
        INNER JOIN eleves ON absences.eleve_id = eleves.id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="content-body">
            <div class="container">
                <h1>Liste des Absences</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Élève</th>
                            <th>Date</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . $row["nom_eleve"] . ' ' . $row["prenom_eleve"] . '</td>
                <td>' . $row["date_absence"] . '</td>
                <td><i class="fas fa-' . ($row["motif"] == "Absent" ? "user-times" : "bed") . '"></i> ' . $row["motif"] . '</td>
            </tr>';
    }
    echo '</tbody>
        </table>
        <p>Nombre total d\'absences: ' . $result->num_rows . '</p>
        </div>
    </div>';
} else {
    echo "Aucune absence enregistrée.";
}

?>

<style>
    /* styles.css */

    .container {
        background-color: #fff;
        border-radius: 20px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        max-width: 700px;
        width: 100%;
    }

    /* En-tête */
    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    /* Tableau des absences */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    /* Icônes */
    i.fas {
        margin-right: 5px;
    }

    /* Sanctions */
    h2 {
        color: #555;
        font-size: 18px;
        margin-bottom: 10px;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        margin-bottom: 5px;
    }

</style>

<?php
include_once '../Dashboard_Staff/liens_utiles/footer.php';
?>
