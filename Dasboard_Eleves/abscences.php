<?php
session_start();
include_once '../connexion/dbcon.php';
include_once '../Dasboard_Eleves/liens_utiles/header.php';

// Assurez-vous que l'email est défini, par exemple en le récupérant de la session
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

if ($email) {
    // Préparation de la requête pour obtenir les informations de l'élève
    $query = "SELECT id, nom, prenom FROM eleves WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Récupérer les informations de l'élève
        $row = $result->fetch_assoc();
        $eleve_id = $row['id'];
    } else {
        die("Aucun élève trouvé avec cet email.");
    }
} else {
    die("Email non défini.");
}

// Requête pour récupérer les absences de l'élève connecté
$sql = "SELECT date_absence, motif
        FROM absences
        WHERE eleve_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $eleve_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<div class="content-body">
    <div class="container">
        <h1>Liste des Absences</h1>
        <?php
        if ($result->num_rows > 0) {
            // Affichage des absences
            echo '<table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . htmlspecialchars($row["date_absence"]) . '</td>
                        <td>' . htmlspecialchars($row["motif"]) . '</td>
                    </tr>';
            }
            echo '</tbody>
                </table>
                <p>Nombre total d\'absences: ' . $result->num_rows . '</p>';
        } else {
            echo "<p>Aucune absence enregistrée.</p>";
        }
        ?>
    </div>
</div>

<style>
    .container {
        background-color: #fff;
        border-radius: 20px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        max-width: 700px;
        width: 100%;
    }

    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }
</style>

<?php
include_once '../Dasboard_Eleves/liens_utiles/footer.php';
?>
