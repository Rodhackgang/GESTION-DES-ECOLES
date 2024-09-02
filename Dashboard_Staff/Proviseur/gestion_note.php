<?php
include_once './liens_utiles/header.php';
include_once '../../connexion/dbcon.php';
function recuperer_notes($conn)
{
    $sql = "SELECT * FROM notes";
    $result = $conn->query($sql);
    return $result;
}

// Récupérer les notes
$notes = recuperer_notes($conn);
?>


<style>
    input[type="number"],
    input[type="date"],
    textarea,
    select {
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
        box-sizing: border-box;
    }

    #gestion-notes {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-container {
        margin-bottom: 20px;
    }

    .form-container h2 {
        margin-top: 0;
        margin-bottom: 10px;
    }

    .form-container label {
        display: block;
        margin-bottom: 5px;
    }

    .form-container input[type="number"],
    .form-container input[type="date"],
    .form-container textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-container button {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .form-container button:hover {
        background-color: #0056b3;
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-container th,
    .table-container td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .table-container th {
        background-color: #f2f2f2;
    }

    .table-container tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table-container tr:hover {
        background-color: #f2f2f2;
    }

    .table-container button {
        padding: 5px 10px;
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
    }

    .table-container button.edit-btn {
        background-color: #ffc107;
        color: #212529;
        margin-right: 5px;
    }
</style>

<div class="content-body">
    <main id="gestion-notes">
        <!-- Formulaire d'ajout de note -->
        <div class="form-container">
            <h2>Ajouter une note</h2>
            <form id="ajout-note-form" method="POST" action="../../Dashboard_Staff/Proviseur/ajout/ajout_note.php">
                <label for="eleve-id">Élève:</label>
                <select id="eleve-id" name="eleve-id" required>
                    <?php
                    // Récupérer la liste des élèves
                    $sql = "SELECT id, nom, prenom FROM eleves";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['nom'] . " " . $row['prenom'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Aucun élève trouvé</option>";
                    }
                    ?>
                </select>
                <br>
                <label for="matiere-id">Matière:</label>
                <select id="matiere-id" name="matiere-id" required>
                    <?php
                    // Récupérer la liste des matières
                    $sql = "SELECT id, nom FROM matieres";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['nom'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Aucune matière trouvée</option>";
                    }

                    ?>
                </select>
                <label for="semestre">Sélectionnez un semestre :</label>
                <select id="semestre" name="semestre">
                    <option value="semestre1">Semestre 1</option>
                    <option value="semestre2">Semestre 2</option>
                    <option value="semestre3">Semestre 3</option>
                </select>

                <br>

                <label for="devoir">Sélectionnez un devoir :</label>
                <select id="devoir" name="devoir">
                    <option value="devoir1">Devoir 1</option>
                    <option value="devoir2">Devoir 2</option>
                </select>
                <br>
                <label for="note">Note:</label>
                <input type="number" step="0.01" id="note" name="note" required>
                <br>
                <label for="date-evaluation">Date d'évaluation:</label>
                <input type="date" id="date-evaluation" name="date-evaluation" required>
                <br>
                <label for="commentaire">Commentaire:</label>
                <textarea id="commentaire" name="commentaire"></textarea>

                <br>
                <label for="classe-id">Classe:</label>
                <select id="classe-id" name="classe-id" required>
                    <?php
                    // Récupérer la liste des classes
                    $sql = "SELECT id, nom FROM salles_classe";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Aucune classe trouvée</option>";
                    }
                    ?>
                </select>

                <br>
                <button type="submit">Ajouter</button>
            </form>
        </div>


        <!-- Tableau des notes -->
        <div class="table-container">
    <h2>Liste des Notes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Élève</th>
                <th>Nom Élève</th>
                <th>Prénom Élève</th>
                <th>ID Matière</th>
                <th>Nom Matière</th>
                <th>Note</th>
                <th>Date d'évaluation</th>
                <th>Nom de salle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT notes.id, 
            eleves.id AS eleve_id, 
            eleves.nom AS eleve_nom, 
            eleves.prenom AS eleve_prenom, 
            matieres.id AS matiere_id, 
            matieres.nom AS matiere_nom, 
            notes.note, 
            notes.date_evaluation, 
            salles_classe.nom AS nom_salle
     FROM notes 
     JOIN eleves ON notes.eleve_id = eleves.id 
     JOIN matieres ON notes.matiere_id = matieres.id
     LEFT JOIN salles_classe ON notes.classe_id = salles_classe.id";

            $notes = $conn->query($sql);
            if ($notes->num_rows > 0) {
                // Afficher les lignes des notes
                while ($row = $notes->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['eleve_id'] . "</td>";
                    echo "<td>" . $row['eleve_nom'] . "</td>";
                    echo "<td>" . $row['eleve_prenom'] . "</td>";
                    echo "<td>" . $row['matiere_id'] . "</td>";
                    echo "<td>" . $row['matiere_nom'] . "</td>";
                    echo "<td>" . $row['note'] . "</td>";
                    echo "<td>" . $row['date_evaluation'] . "</td>";
                    echo "<td>" . $row['nom_salle'] . "</td>";
                    echo "<td>
                        <button class='edit-btn'>Modifier</button>
                        <button class='delete-btn'>Supprimer</button>
                      </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>Aucune note trouvée.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    </main>

</div>
<script>
    function supprimerPersonnel(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer ce membre du personnel ?")) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Redirection vers la page actuelle après la suppression réussie
                    location.reload();
                }
            };
            xhr.open("POST", "../../Dashboard_Staff/Proviseur/supprimer/supprimer_note.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("id=" + id);
        }
    }

    // Ajouter un événement de clic à chaque bouton "Supprimer"
    var deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
            supprimerPersonnel(id);
        });
    });


    function chargerDonneesPersonnel(id) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var note = JSON.parse(xhr.responseText);
                if (!note.error) {
                    document.getElementById('eleve-id').value = note.eleve_id;
                    document.getElementById('matiere-id').value = note.matiere_id;
                    document.getElementById('semestre').value = note.semestre;
                    document.getElementById('devoir').value = note.devoir;
                    document.getElementById('note').value = note.note;
                    document.getElementById('date-evaluation').value = note.date_evaluation;
                    document.getElementById('commentaire').value = note.commentaire; // Remplissage du champ commentaire

                    var submitBtn = document.querySelector('#ajout-note-form button[type="submit"]');
                    submitBtn.textContent = "Modifier";
                    document.getElementById('ajout-note-form').action = "../../Dashboard_Staff/Proviseur/modifier/modifier_notes.php";
                    if (!document.querySelector('input[name="id"]')) {
                        document.getElementById('ajout-note-form').insertAdjacentHTML('beforeend', '<input type="hidden" name="id" value="' + id + '">');
                    } else {
                        document.querySelector('input[name="id"]').value = id;
                    }
                } else {
                    alert(note.error);
                }
            }
        };
        xhr.open("POST", "../../Dashboard_Staff/Proviseur/modifier/modifier_note.php?id=" + id, true);
        xhr.send();
    }

    // Ajouter un événement de clic à chaque bouton "Modifier"
    document.querySelectorAll('.edit-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
            chargerDonneesPersonnel(id); // Cette ligne appelle la fonction chargerDonneesPersonnel avec l'ID récupéré
        });
    });

    // Fonction pour effacer les champs du formulaire
    function effacerChamps() {
        document.getElementById('nom_personnel').value = '';
        document.getElementById('prenom_personnel').value = '';
        document.getElementById('role_personnel').value = 'enseignant';
        document.getElementById('responsabilites').value = '';
        document.getElementById('horaires').value = '';
        document.getElementById('disponibilite').value = '';
        document.getElementById('telephone').value = '';
        document.getElementById('email').value = '';
        document.getElementById('date_embauche').value = '';

        // Modifier le texte du bouton de soumission pour "Ajouter"
        var submitBtn = document.querySelector('#ajout-personnel-form button[type="submit"]');
        submitBtn.textContent = "Ajouter";
        // Réinitialiser l'action du formulaire pour ajout_personnel.php
        document.getElementById('ajout-personnel-form').action = "../../Dashboard_Staff/Proviseur/ajout/ajout_personnel.php";
        // Supprimer le champ caché de l'ID du personnel s'il existe
        var idInput = document.querySelector('input[name="id"]');
        if (idInput) {
            idInput.parentNode.removeChild(idInput);
        }
    }

    // Appeler la fonction pour effacer les champs lorsque la page est chargée
    window.addEventListener('load', effacerChamps);
</script>

<?php
include_once './liens_utiles/footer.php';
?>