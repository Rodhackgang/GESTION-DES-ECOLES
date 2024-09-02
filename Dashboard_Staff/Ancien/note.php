<?php
session_start();
include_once '../Dasboard_Eleves/liens_utiles/header.php';
?>

<div class="content-body">
<?php
include_once '../connexion/dbcon.php';

// Vérifie si l'adresse e-mail de l'élève est définie dans la session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Recherche de l'élève par son adresse e-mail
    $query = "SELECT id, nom, prenom FROM eleves WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Récupérer les informations de l'élève
        $row = $result->fetch_assoc();
        $eleve_id = $row['id'];
        $nom_eleve = $row['nom'] . " " . $row['prenom'];

        // Afficher les notes de l'élève
        $query_notes = "SELECT matiere_id, note FROM notes WHERE eleve_id = ?";
        $stmt_notes = $conn->prepare($query_notes);
        $stmt_notes->bind_param("i", $eleve_id);
        $stmt_notes->execute();
        $result_notes = $stmt_notes->get_result();

        // Structure HTML pour afficher les notes
        ?>
        <div class="container">
            <h1>Notes de <?php echo $nom_eleve; ?></h1>
            <?php
            // Parcourir les résultats des notes
            while ($row_notes = $result_notes->fetch_assoc()) {
                // Vous pouvez remplacer les valeurs statiques par les valeurs réelles
                $matiere_id = $row_notes['matiere_id'];
                $note = $row_notes['note'];

                // Afficher les notes pour chaque matière et chaque devoir
                ?>
                <h2>Matière <?php echo $matiere_id; ?></h2>
                <table>
                    <thead>
                        <tr>
                            <th>Devoir</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Devoir 1</td>
                            <td><span><?php echo $note; ?></span></td>
                        </tr>
                        <!-- Ajoutez les autres devoirs ici -->
                    </tbody>
                </table>
                <?php
            }
            ?>
            <!-- Afficher la moyenne générale et le statut ici -->
            <hr>
            <h2>Status</h2>
            <?php
// Calculer la moyenne des notes de l'élève
$sql = "SELECT AVG(note) AS moyenne FROM notes WHERE eleve_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $row['id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $moyenne = $row['moyenne'];
} else {
    $moyenne = 0; // Si l'élève n'a pas de notes, la moyenne est définie à 0
}

// Déterminer le texte du status
$status_text = "";
if ($moyenne > 10) {
    // L'élève est admissible
    $status_text = "Admissible";
    $status_color = "green"; // Couleur verte
} else {
    // L'élève risque d'être banni
    $status_text = "Vous risquez d'être banni";
    $status_color = "red"; // Couleur rouge
}

// Afficher le point de statut avec le texte approprié
echo "<div class='status'>";
echo "<span class='dot' style='background-color: $status_color;'></span>";
echo "<span class='status-text'>$status_text</span>";
echo "</div>";
?>

            <hr>
            <!-- Afficher le statut -->
        </div>
        <?php
    } else {
        echo "Aucun élève trouvé avec cette adresse e-mail.";
    }
} else {
    echo "L'adresse e-mail de l'élève n'est pas définie dans la session.";
}
?>

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
    .status {
        display: flex;
        align-items: center;
        margin-top: 20px;
    }

    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .status-text {
        color: #333;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    th,
    td {
        padding: 8px;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
</style>
<script>
    // Remplacer les "--" par les notes réelles
    document.getElementById('noteDevoir1').textContent = 15; // Remplacer par la note réelle du devoir 1
    document.getElementById('noteDevoir2').textContent = 12; // Remplacer par la note réelle du devoir 2
    document.getElementById('noteDevoir3').textContent = 18; // Remplacer par la note réelle du devoir 3
</script>

<?php
include_once '../Dasboard_Eleves/liens_utiles/footer.php';
?>