<?php
include_once './liens_utiles/header.php';
include_once '../../connexion/dbcon.php';
?>
<div class="content-body">
    <main id="gestion-bulletins">
        <!-- Formulaire d'ajout de bulletin -->
        <div class="form-container">
            <h2>Ajouter un bulletin de note</h2>
            <form id="ajout-bulletin-form" method="POST" action="../../Dashboard_Staff/Proviseur/ajout/ajout_bulletin.php">
                <label for="eleve-id">Élève:</label>
                <select id="eleve-id" name="eleve-id" required>
                    <?php
                    $result = $conn->query("SELECT id, CONCAT(nom, ' ', prenom) AS nom_complet FROM eleves");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['nom_complet'] . "</option>";
                    }
                    ?>
                </select>

                <label for="semestre">Semestre:</label>
                <select id="semestre" name="semestre" required>
                    <option value="Semestre 1">Semestre 1</option>
                    <option value="Semestre 2">Semestre 2</option>
                    <option value="Semestre 3">Semestre 3</option>
                </select>

                <label for="annee-scolaire-id">Année scolaire:</label>
                <select id="annee-scolaire-id" name="annee-scolaire-id" required>
                    <?php
                    $result = $conn->query("SELECT id, CONCAT(annee_debut, '-', annee_fin) AS annee FROM annees_scolaires");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['annee'] . "</option>";
                    }
                    ?>
                </select>

                <label for="classe-id">Classe:</label>
                <select id="classe-id" name="classe-id" required>
                    <?php
                    $result = $conn->query("SELECT id, nom FROM salles_classe");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                    }
                    ?>
                </select>

                <label for="commentaires">Commentaires:</label>
                <textarea id="commentaires" name="commentaires" readonly></textarea>

                <button type="submit">Ajouter</button>
            </form>

            <script>
                document.getElementById('semestre').addEventListener('change', function() {
                    let eleveId = document.getElementById('eleve-id').value;
                    let semestre = this.value;

                    if (eleveId && semestre) {
                        fetch(`./get_notes.php?eleve_id=${eleveId}&semestre=${semestre}`)
                            .then(response => response.json())
                            .then(data => {
                                let totalNotes = 0;
                                let nbMatieres = data.length;

                                data.forEach(note => {
                                    totalNotes += parseFloat(note.note);
                                });

                                let moyenne = totalNotes / nbMatieres;
                                let commentaire = '';

                                if (moyenne >= 18) {
                                    commentaire = 'Excellent';
                                } else if (moyenne >= 15) {
                                    commentaire = 'Très bien';
                                } else if (moyenne >= 12) {
                                    commentaire = 'Bien, continuez comme ça';
                                } else if (moyenne >= 10) {
                                    commentaire = 'Passable';
                                } else {
                                    commentaire = 'Vous risquez d\'être banni';
                                }

                                document.getElementById('moyenne-generale').value = moyenne.toFixed(2);
                                document.getElementById('commentaires').value = commentaire;
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });

                document.getElementById('eleve-id').addEventListener('change', function() {
                    let semestre = document.getElementById('semestre').value;
                    let eleveId = this.value;

                    if (eleveId && semestre) {
                        fetch(`./get_notes.php?eleve_id=${eleveId}&semestre=${semestre}`)
                            .then(response => response.json())
                            .then(data => {
                                let totalNotes = 0;
                                let nbMatieres = data.length;

                                data.forEach(note => {
                                    totalNotes += parseFloat(note.note);
                                });

                                let moyenne = totalNotes / nbMatieres;
                                let commentaire = '';

                                if (moyenne >= 18) {
                                    commentaire = 'Excellent';
                                } else if (moyenne >= 15) {
                                    commentaire = 'Très bien';
                                } else if (moyenne >= 12) {
                                    commentaire = 'Bien, continuez comme ça';
                                } else if (moyenne >= 10) {
                                    commentaire = 'Passable';
                                } else {
                                    commentaire = 'Vous risquez d\'être banni';
                                }

                                document.getElementById('moyenne-generale').value = moyenne.toFixed(2);
                                document.getElementById('commentaires').value = commentaire;
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            </script>


        </div>
        <div class="table-container">
            <h2>Liste des Bulletins de Notes</h2>
            <?php
            function calculer_somme_notes($conn, $eleveId)
            {
                // Requête pour récupérer la somme des notes de l'élève
                $query = "SELECT SUM(note) AS somme_des_notes FROM notes WHERE eleve_id = ?";
                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param('i', $eleveId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $somme_notes = 0;

                    if ($row = $result->fetch_assoc()) {
                        $somme_notes = $row['somme_des_notes'];
                    }

                    $stmt->close();

                    return $somme_notes;
                } else {
                    return null;
                }
            }

            // Récupérer les bulletins
            $query = "SELECT bulletins.id, eleves.nom, eleves.prenom, salles_classe.nom AS classe, CONCAT(annees_scolaires.annee_debut, '-', annees_scolaires.annee_fin) AS annee, bulletins.semestre, bulletins.commentaires, bulletins.eleve_id 
            FROM bulletins 
            JOIN eleves ON bulletins.eleve_id = eleves.id 
            JOIN salles_classe ON eleves.classe_id = salles_classe.id
            JOIN annees_scolaires ON bulletins.annee_scolaire_id = annees_scolaires.id";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo '<table border="1">';
                echo '<tr><th>ID de l\'élève</th><th>Nom de l\'élève</th><th>Année scolaire</th><th>Semestre</th><th>Somme des notes</th><th>Moyenne Generale</th><th>Classe</th><th>Actions</th></tr>';
                while ($row = $result->fetch_assoc()) {
                    $somme_notes = calculer_somme_notes($conn, $row['eleve_id']);
                    if (is_null($somme_notes)) {
                        $somme_notes = 0;
                    }
                    $moyenne = (float) $somme_notes / 2;
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['eleve_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['prenom']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['annee']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['semestre']) . '</td>';
                    echo '<td>' . htmlspecialchars($somme_notes) . '</td>';
                    echo '<td>' . htmlspecialchars($moyenne) . '</td>';
                    echo '<td>' . htmlspecialchars($row['classe']) . '</td>';
                    echo '<td>
                <a href="./page_bulletins.php?id=' . htmlspecialchars($row['id']) . '">Imprimer</a>
              </td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo 'Aucun bulletin trouvé.';
            }
            ?>
        </div>

</div>

</div>
</main>

</div>
<style>
    /* styles.css */

    #gestion-bulletins {
        margin: 20px;
    }

    .form-container,
    .table-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    h2 {
        margin-top: 0;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    form label {
        margin-top: 10px;
    }

    form input,
    form textarea,
    form select {
        padding: 8px;
        margin-top: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    form textarea {
        resize: vertical;
        height: 100px;
    }

    form button {
        margin-top: 20px;
        padding: 10px 15px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        padding: 10px;
        border-bottom: 1px solid #ccc;
    }

    table th {
        background-color: #f0f0f0;
    }

    table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tbody tr:hover {
        background-color: #e0e0e0;
    }

    .edit-btn,
    .delete-btn,
    .imprim-btn {
        padding: 5px 10px;
        margin: 2px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
</style>
<?php
include_once './liens_utiles/footer.php';
?>