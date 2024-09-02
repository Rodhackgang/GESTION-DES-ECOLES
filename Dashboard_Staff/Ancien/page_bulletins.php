<?php
include_once '../Dashboard_Staff/liens_utiles/header.php';
include_once '../connexion/dbcon.php';

if (!isset($_GET['id'])) {
    die('Bulletin ID manquant.');
}

$bulletinId = intval($_GET['id']);

// Récupérer les informations du bulletin
$query = "SELECT bulletins.id, eleves.nom, eleves.prenom, eleves.date_naissance, eleves.adresse, eleves.telephone, eleves.email, bulletins.semestre, CONCAT(annees_scolaires.annee_debut, '-', annees_scolaires.annee_fin) AS annee, bulletins.commentaires, bulletins.eleve_id 
          FROM bulletins 
          JOIN eleves ON bulletins.eleve_id = eleves.id 
          JOIN annees_scolaires ON bulletins.annee_scolaire_id = annees_scolaires.id
          WHERE bulletins.id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('i', $bulletinId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bulletin = $result->fetch_assoc();
    $stmt->close();
} else {
    die("Erreur de préparation de la requête: " . $conn->error);
}

// Récupérer la liste des matières et des notes
$notesQuery = "SELECT matieres.nom AS matiere, notes.note, notes.date_evaluation 
               FROM notes 
               JOIN matieres ON notes.matiere_id = matieres.id 
               WHERE notes.eleve_id = ?";
if ($notesStmt = $conn->prepare($notesQuery)) {
    $notesStmt->bind_param('i', $bulletin['eleve_id']);
    $notesStmt->execute();
    $notesResult = $notesStmt->get_result();
    $notes = [];
    while ($row = $notesResult->fetch_assoc()) {
        $notes[] = $row;
    }
    $notesStmt->close();
} else {
    die("Erreur de préparation de la requête des notes: " . $conn->error);
}

$dateDuJour = date("d/m/Y");
$anneeActuelle = date("Y");

// Calculer l'année scolaire actuelle
$anneeDebut = date("Y") - (date("m") < 7 ? 1 : 0);
$anneeFin = $anneeDebut + 1;
$anneeScolaireActuelle = "$anneeDebut-$anneeFin";

?>

<div class="content-body">
    <div class="container">


        <div class="notes">
            <div class="left">
                <p><?php echo $dateDuJour; ?></p>
                <p>Unité-Progrès-Justice</p>
            </div>
            <h1>Bulletin de Notes</h1>
        </div>
        <h2>Année Scolaire: <?php echo htmlspecialchars($bulletin['annee']); ?></h2>
        <h3>Semestre: <?php echo htmlspecialchars($bulletin['semestre']); ?></h3>
    
    <div class="details">
        <table>
            <tr>
                <th>Nom de l'élève</th>
                <td><?php echo htmlspecialchars($bulletin['nom'] . ' ' . $bulletin['prenom']); ?></td>
            </tr>
            <tr>
                <th>Date de Naissance</th>
                <td><?php echo htmlspecialchars($bulletin['date_naissance']); ?></td>
            </tr>
            <tr>
                <th>Adresse</th>
                <td><?php echo htmlspecialchars($bulletin['adresse']); ?></td>
            </tr>
            <tr>
                <th>Téléphone</th>
                <td><?php echo htmlspecialchars($bulletin['telephone']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($bulletin['email']); ?></td>
            </tr>
        </table>
    </div>

    <div class="notes">
        <h4>Notes obtenues :</h4>
        <table>
            <tr>
                <th>Matière</th>
                <th>Note</th>
                <th>Date d'Évaluation</th>
            </tr>
            <?php foreach ($notes as $note) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($note['matiere']); ?></td>
                    <td><?php echo htmlspecialchars($note['note']); ?></td>
                    <td><?php echo htmlspecialchars($note['date_evaluation']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="notes">
        <?php
        $moyennes = [];
        foreach ($notes as $note) {
            $matiere = $note['matiere'];
            $noteValue = floatval($note['note']);
            if (!isset($moyennes[$matiere])) {
                $moyennes[$matiere] = ['total' => 0, 'count' => 0];
            }
            $moyennes[$matiere]['total'] += $noteValue;
            $moyennes[$matiere]['count']++;
        }

        // Afficher la moyenne des notes par matière
        ?>
        <h4>Moyenne par matière :</h4>
        <table>
            <tr>
                <th>Matière</th>
                <th>Moyenne</th>
            </tr>
            <?php foreach ($moyennes as $matiere => $data) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($matiere); ?></td>
                    <td><?php echo $data['total'] / $data['count'] . " de moyenne"; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    </table>

    <?php
    // Calculer la moyenne générale
    $totalMoyennes = 0;
    $nombreMatieres = count($moyennes);
    foreach ($moyennes as $matiere => $data) {
        $totalMoyennes += $data['total'] / $data['count'];
    }
    $moyenneGenerale = $totalMoyennes / $nombreMatieres;
    $moyenneGeneraleFormatted = number_format($moyenneGenerale, 2);

    // Afficher le message en fonction de la moyenne générale
    $message = '';
    $pointColor = '';
    if ($moyenneGenerale < 10) {
        $message = "Vous avez une moyenne générale de $moyenneGeneraleFormatted. Faites un effort, sinon vous serez banni.";
        $pointColor = 'red';
    } elseif ($moyenneGenerale >= 10 && $moyenneGenerale < 15) {
        $message = "Vous avez une moyenne générale de $moyenneGeneraleFormatted. Continuez toujours avec cet élan de combativité.";
        $pointColor = 'green';
    } else {
        $message = "Excellent ! Vous avez une moyenne générale de $moyenneGeneraleFormatted. Si vous continuez comme ça, vous serez récompensé en fin d'année.";
        $pointColor = 'green';
    }
    ?>

    <div class="notes">
        <h4>Moyenne générale : <?php echo $moyenneGeneraleFormatted; ?></h4>
        <div class="message" style="color: <?php echo $pointColor; ?>">
            <p><?php echo $message; ?></p>
        </div>
    </div>
    </table>
    </div>
</div>
</div>
</div>

<div class="print">
    Imprimer
</div>
</div>

<?php
include_once '../Dashboard_Staff/liens_utiles/footer.php';
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var printButton = document.querySelector(".print");

        printButton.addEventListener("click", function() {
            var container = document.querySelector(".container").innerHTML;

            var printWindow = window.open('', '', 'height=400,width=800');

            printWindow.document.write('<html><head><title>Impression</title>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(container);
            printWindow.document.write('</body></html>');

            printWindow.document.close();
            printWindow.print();
        });
    });
</script>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f9f9f9;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: 40px auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #4CAF50;
        padding-bottom: 10px;
    }

    .header .left p {
        margin: 0;
        font-weight: bold;
    }

    h1,
    h2,
    h3,
    h4 {
        text-align: center;
        color: #4CAF50;
    }

    .details,
    .notes {
        margin: 20px 0;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .details table,
    .notes table {
        width: 100%;
        border-collapse: collapse;
    }

    .details th,
    .notes th,
    .details td,
    .notes td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    .footer {
        text-align: center;
        margin-top: 20px;
        font-size: 1.2em;
        font-weight: bold;
        color: #4CAF50;
    }

    .print {
        display: block;
        width: 150px;
        margin: 20px auto;
        padding: 10px;
        text-align: center;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .print:hover {
        background-color: #45a049;
    }

    @media print {
        .print {
            display: none;
        }

        body {
            background-color: white;
        }

        .container {
            box-shadow: none;
        }
    }
</style>