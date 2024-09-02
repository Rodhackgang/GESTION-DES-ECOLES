<?php
session_start();
include_once '../connexion/dbcon.php';
include_once '../Dasboard_Eleves/liens_utiles/header.php';

// Assurez-vous que l'email est défini, par exemple en le récupérant de la session
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

if ($email) {
    // Préparation de la requête
    $query = "SELECT id, nom, prenom FROM eleves WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Récupérer les informations de l'élève
        $row = $result->fetch_assoc();
        $eleve_id = $row['id'];

        // Requête pour récupérer les informations de l'élève
        $sql_eleve = "SELECT * FROM eleves WHERE id = ?";
        $stmt_eleve = $conn->prepare($sql_eleve);
        $stmt_eleve->bind_param("i", $eleve_id);
        $stmt_eleve->execute();
        $result_eleve = $stmt_eleve->get_result();
        $eleve = $result_eleve->fetch_assoc();
    } else {
        die("Aucun élève trouvé avec cet email.");
    }
} else {
    die("Email non défini.");
}
?>

<div class="content-body">
    <div class="container">
        <header>
            <h1>Bienvenue, <?php echo htmlspecialchars($eleve['prenom'] . ' ' . $eleve['nom']); ?></h1>
        </header>
        <main>
            <section class="student-info">
                <h2>Informations Personnelles</h2>
                <p><strong>Nom:</strong> <?php echo htmlspecialchars($eleve['nom']); ?></p>
                <p><strong>Prénom:</strong> <?php echo htmlspecialchars($eleve['prenom']); ?></p>
                <p><strong>Date de Naissance:</strong> <?php echo htmlspecialchars($eleve['date_naissance']); ?></p>
                <p><strong>Adresse:</strong> <?php echo htmlspecialchars($eleve['adresse']); ?></p>
                <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($eleve['telephone']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($eleve['email']); ?></p>
                <p><strong>Date d'Inscription:</strong> <?php echo htmlspecialchars($eleve['date_inscription']); ?></p>
            </section>

            <section class="grades">
                <h2>Notes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Matière</th>
                            <th>Note</th>
                            <th>Date</th>
                            <th>Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_notes = "SELECT matieres.nom AS matiere, notes.note, notes.date_evaluation, notes.commentaire 
                                      FROM notes 
                                      JOIN matieres ON notes.matiere_id = matieres.id 
                                      WHERE notes.eleve_id = ?";
                        $stmt_notes = $conn->prepare($sql_notes);
                        $stmt_notes->bind_param("i", $eleve_id);
                        $stmt_notes->execute();
                        $result_notes = $stmt_notes->get_result();

                        if ($result_notes->num_rows > 0) {
                            while($row = $result_notes->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['matiere']) . "</td>
                                    <td>" . htmlspecialchars($row['note']) . "</td>
                                    <td>" . htmlspecialchars($row['date_evaluation']) . "</td>
                                    <td>" . htmlspecialchars($row['commentaire']) . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Aucune note trouvée</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <section class="schedule">
                <h2>Emploi du Temps</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Heure de Début</th>
                            <th>Heure de Fin</th>
                            <th>Matière</th>
                            <th>Enseignant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_schedule = "SELECT emplois_temps.jour, emplois_temps.heure_debut, emplois_temps.heure_fin, matieres.nom AS matiere, staffs.nom AS enseignant 
                                         FROM emplois_temps 
                                         JOIN matieres ON emplois_temps.matiere_id = matieres.id 
                                         JOIN staffs ON emplois_temps.staff_id = staffs.id 
                                         WHERE emplois_temps.classe_id = (SELECT classe_id FROM eleves WHERE id = ?)";
                        $stmt_schedule = $conn->prepare($sql_schedule);
                        $stmt_schedule->bind_param("i", $eleve_id);
                        $stmt_schedule->execute();
                        $result_schedule = $stmt_schedule->get_result();

                        if ($result_schedule->num_rows > 0) {
                            while($row = $result_schedule->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['jour']) . "</td>
                                    <td>" . htmlspecialchars($row['heure_debut']) . "</td>
                                    <td>" . htmlspecialchars($row['heure_fin']) . "</td>
                                    <td>" . htmlspecialchars($row['matiere']) . "</td>
                                    <td>" . htmlspecialchars($row['enseignant']) . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Aucun emploi du temps trouvé</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <section class="absences">
                <h2>Absences</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_absences = "SELECT date_absence, motif FROM absences WHERE eleve_id = ?";
                        $stmt_absences = $conn->prepare($sql_absences);
                        $stmt_absences->bind_param("i", $eleve_id);
                        $stmt_absences->execute();
                        $result_absences = $stmt_absences->get_result();

                        if ($result_absences->num_rows > 0) {
                            while($row = $result_absences->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['date_absence']) . "</td>
                                    <td>" . htmlspecialchars($row['motif']) . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>Aucune absence trouvée</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <section class="notifications">
                <h2>Notifications</h2>
                <ul>
                    <?php
                    $sql_notifications = "SELECT message, date_envoi FROM notifications WHERE destinataire_id = ?";
                    $stmt_notifications = $conn->prepare($sql_notifications);
                    $stmt_notifications->bind_param("i", $eleve_id); // Utiliser l'ID de l'élève comme destinataire
                    $stmt_notifications->execute();
                    $result_notifications = $stmt_notifications->get_result();

                    if ($result_notifications->num_rows > 0) {
                        while($row = $result_notifications->fetch_assoc()) {
                            echo "<li>" . htmlspecialchars($row['date_envoi']) . " - " . htmlspecialchars($row['message']) . "</li>";
                        }
                    } else {
                        echo "<li>Aucune notification trouvée</li>";
                    }
                    ?>
                </ul>
            </section>
        </main>
    </div>
</div>

    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: 0 auto;
}


main {
    padding: 20px;
    background: #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h1, h2 {
    color: #333;
}

section {
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

ul {
    list-style-type: none;
    padding: 0;
}



    </style>
<?php
    include_once '../Dasboard_Eleves/liens_utiles/footer.php';
?>