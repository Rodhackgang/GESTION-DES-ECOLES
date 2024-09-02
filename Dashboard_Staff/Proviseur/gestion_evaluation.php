<?php
include_once './liens_utiles/header.php';
include_once '../../connexion/dbcon.php';
?>
<div class="content-body">
    <main id="gestion-evaluations">
        <!-- Formulaire d'ajout d'évaluation -->
        <div class="form-container">
            <h2>Ajouter une évaluation</h2>
            <form id="ajout-evaluation-form" method="POST" action="../../Dashboard_Staff/Proviseur/ajout/ajout_evaluation.php">
                <label for="matiere_id">Matière:</label>
                <select id="matiere_id" name="matiere_id" required>
                    <?php
                    $result = $conn->query("SELECT id, nom FROM matieres");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                        }
                    }
                    ?>
                </select>

                <label for="classe_id">Classe:</label>
                <select id="classe_id" name="classe_id" required>
                    <?php
                    $result = $conn->query("SELECT id, nom FROM salles_classe");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                        }
                    }
                    ?>
                </select>

                <label for="titre">Titre:</label>
                <input type="text" id="titre" name="titre" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="date_evaluation">Date de l'évaluation:</label>
                <input type="date" id="date_evaluation" name="date_evaluation" required>

                <label for="annee_scolaire_id">Année Scolaire:</label>
                <select id="annee_scolaire_id" name="annee_scolaire_id" required>
                    <?php
                    $result = $conn->query("SELECT id, annee_debut, annee_fin FROM annees_scolaires");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['annee_debut'] . "-" . $row['annee_fin'] . "</option>";
                        }
                    }
                    ?>
                </select>

                <button type="submit">Ajouter</button>
            </form>


        </div>

        <!-- Tableau des évaluations -->
        <div class="table-container">
    <h2>Liste des Évaluations</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Matière</th>
                <th>Classe</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Date de l'évaluation</th>
                <th>Année Scolaire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Inclure la connexion à la base de données
            include('../../connexion/dbcon.php');

            // Requête pour récupérer les évaluations avec les détails de la matière, de la classe et de l'année scolaire
            $query = "
                SELECT evaluations.id, matieres.nom AS matiere, salles_classe.nom AS classe, evaluations.titre, evaluations.description, evaluations.date_evaluation, annees_scolaires.annee_debut, annees_scolaires.annee_fin
                FROM evaluations
                INNER JOIN matieres ON evaluations.matiere_id = matieres.id
                INNER JOIN salles_classe ON evaluations.classe_id = salles_classe.id
                INNER JOIN annees_scolaires ON evaluations.annee_scolaire_id = annees_scolaires.id";
            $result = $conn->query($query);

            // Vérifie si des évaluations ont été trouvées
            if ($result->num_rows > 0) {
                // Boucle à travers chaque évaluation et affiche les détails dans une ligne du tableau
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['matiere'] . "</td>";
                    echo "<td>" . $row['classe'] . "</td>";
                    echo "<td>" . $row['titre'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>" . $row['date_evaluation'] . "</td>";
                    echo "<td>" . $row['annee_debut'] . "-" . $row['annee_fin'] . "</td>";
                    echo "<td>
                        <button class='edit-btn' onclick='editEvaluation(" . $row['id'] . ")'>Modifier</button>
                        <button class='delete-btn' data-id='" . $row['id'] . "'>Supprimer</button>
                    </td>";
                    echo "</tr>";
                }
            } else {
                // Si aucune évaluation n'est trouvée dans la base de données
                echo "<tr><td colspan='8'>Aucune évaluation trouvée.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    </main>
</div>
<script>
  var deleteButtons = document.querySelectorAll(".delete-btn");
deleteButtons.forEach(function(button) {
    button.addEventListener("click", function() {
        // Récupérer l'ID de l'évaluation associée à ce bouton "Supprimer"
        var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;

        // Demander confirmation avant de supprimer
        var confirmDelete = confirm("Êtes-vous sûr de vouloir supprimer cette évaluation ?");

        if (confirmDelete) {
            // Envoyer une requête AJAX au serveur pour supprimer l'évaluation avec l'ID récupéré
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../../Dashboard_Staff/Proviseur/supprimer/supprimer_evaluation.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Mettre à jour le tableau après la suppression de l'évaluation
                    location.reload();
                }
            };
            xhr.send("id=" + id);
        }
    });
});


    function chargerDonneesEvaluation(id) {
        // Récupérer les données de l'évaluation via une requête AJAX
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var evaluation = JSON.parse(xhr.responseText);
                document.getElementById('matiere_id').value = evaluation.matiere_id;
                document.getElementById('titre').value = evaluation.titre;
                document.getElementById('description').value = evaluation.description;
                document.getElementById('date_evaluation').value = evaluation.date_evaluation;

                // Modifier le texte du bouton de soumission pour "Modifier"
                var submitBtn = document.querySelector('#ajout-evaluation-form button[type="submit"]');
                submitBtn.textContent = "Modifier";
                // Mettre à jour l'action du formulaire pour modifier_evaluation.php
                document.getElementById('ajout-evaluation-form').action = "../../Dashboard_Staff/Proviseur/modifier/modifier_evaluations.php";
                // Ajouter un champ caché pour transmettre l'ID de l'évaluation
                document.getElementById('ajout-evaluation-form').insertAdjacentHTML('beforeend', '<input type="hidden" name="id" value="' + id + '">');
            }
        };
        xhr.open("GET", "../../Dashboard_Staff/Proviseur/modifier/modifier_evaluation.php?id=" + id, true);
        xhr.send();
    }

    // Attacher un événement de clic à chaque bouton "Modifier"
    var editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
            chargerDonneesEvaluation(id);
        });
    });
</script>


</main>
</div>
<style>
    #gestion-evaluations {
        margin: 20px;
    }

    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        outline: none;
        transition: border-color 0.3s ease;
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
    form textarea {
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
    .delete-btn {
        padding: 5px 10px;
        margin: 2px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .edit-btn {
        background-color: #28a745;
        color: #fff;
    }

    .edit-btn:hover {
        background-color: #218838;
    }

    .delete-btn {
        background-color: #dc3545;
        color: #fff;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }
</style>

<?php
include_once './liens_utiles/footer.php';
?>