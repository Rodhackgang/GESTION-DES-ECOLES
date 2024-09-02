<?php
include_once './liens_utiles/header.php';
include_once '../../connexion/dbcon.php';
?>
<div class="content-body">
    <main id="suivi-absences">
        <!-- Formulaire d'ajout d'absence -->
        <?php
// Affichage des messages
if (isset($_GET['message'])) {
    if ($_GET['message'] == 'success') {
        echo "<p>Nouvelle absence ajoutée avec succès.</p>";
    } elseif ($_GET['message'] == 'error' && isset($_GET['error'])) {
        echo "<p>Erreur lors de l'ajout de l'absence: " . htmlspecialchars($_GET['error']) . "</p>";
    }
}
?>

<div class="form-container">
    <h2>Ajouter une absence</h2>
    <form id="ajout-absence-form" method="POST" action="../../Dashboard_Staff/Proviseur/ajout/ajout_abscence.php">
        <label for="eleve-id">Élève:</label>
        <select id="eleve-id" name="eleve-id" required>
            <?php

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

        <label for="date-absence">Date de l'absence:</label>
        <input type="date" id="date-absence" name="date-absence" required>
        <br>

        <label for="motif">Motif:</label>
        <textarea id="motif" name="motif"></textarea>
        <br>

        <button type="submit">Ajouter</button>
    </form>
</div>


        <!-- Tableau des absences -->
        <div class="table-container">
            <h2>Liste des Absences</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID de l'élève</th>
                        <th>Date de l'absence</th>
                        <th>Motif</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $query = "SELECT * FROM absences";
                $result = $conn->query($query);

                // Vérifier s'il y a des données à afficher
                if ($result->num_rows > 0) {
                    // Sortir les données de chaque ligne
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['eleve_id']}</td>
                        <td>{$row['date_absence']}</td>
                        <td>" . htmlspecialchars($row['motif']) . "</td>
                        <td>
                            
                            <button class='delete-btn'>Supprimer</button>
                        </td>
                    </tr>";
                    }
                } else {
                    // Afficher un message si aucune absence n'est trouvée
                    echo "<tr><td colspan='5'>Aucune absence trouvée</td></tr>";
                }
                $conn->close();
                ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<script>
    function supprimerAbsence(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette absence ?")) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    location.reload();
                }
            };
            xhr.open("POST", "../../Dashboard_Staff/Proviseur/supprimer/supprimer_absence.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("id=" + id);
            location.reload();
        }
    }

    // Ajouter un événement de clic à chaque bouton "Supprimer"
    var deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
            supprimerAbsence(id);
        });
    });

    function chargerDonneesAbsence(id) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var absence = JSON.parse(xhr.responseText);
                if (!absence.error) {
                    document.getElementById('eleve_id').value = absence.eleve_id;
                    document.getElementById('date_absence').value = absence.date_absence;
                    document.getElementById('motif').value = absence.motif;

                    // Modifier le texte du bouton de soumission pour "Modifier"
                    var submitBtn = document.querySelector('#ajout-absence-form button[type="submit"]');
                    submitBtn.textContent = "Modifier";
                    // Mettre à jour l'action du formulaire pour modifier_absence.php
                    document.getElementById('ajout-absence-form').action = "../../Dashboard_Staff/Proviseur/modifier/modifier_absence.php";
                    // Ajouter un champ caché pour transmettre l'ID de l'absence
                    if (!document.querySelector('input[name="id"]')) {
                        document.getElementById('ajout-absence-form').insertAdjacentHTML('beforeend', '<input type="hidden" name="id" value="' + id + '">');
                    } else {
                        document.querySelector('input[name="id"]').value = id;
                    }
                } else {
                    alert(absence.error);
                }
            }
        };
        xhr.open("GET", "../../Dashboard_Staff/Proviseur/modifier/modifier_absence.php?id=" + id, true);
        xhr.send();
    }

    // Ajouter un événement de clic à chaque bouton "Modifier"
    document.querySelectorAll('.edit-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
            chargerDonneesAbsence(id);
        });
    });

    // Fonction pour effacer les champs du formulaire
    function effacerChamps() {
        document.getElementById('eleve_id').value = '';
        document.getElementById('date_absence').value = '';
        document.getElementById('motif').value = '';

        // Modifier le texte du bouton de soumission pour "Ajouter"
        var submitBtn = document.querySelector('#ajout-absence-form button[type="submit"]');
        submitBtn.textContent = "Ajouter";
        // Réinitialiser l'action du formulaire pour ajout_absence.php
        document.getElementById('ajout-absence-form').action = "../../Dashboard_Staff/Proviseur/ajout/ajout_absence.php";
        // Supprimer le champ caché de l'ID de l'absence s'il existe
        var idInput = document.querySelector('input[name="id"]');
        if (idInput) {
            idInput.parentNode.removeChild(idInput);
        }
    }

    // Appeler la fonction pour effacer les champs lorsque la page est chargée
    window.addEventListener('load', effacerChamps);
</script>

    <style>
        /* styles.css */

        #suivi-absences {
            margin: 20px;
        }

        select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
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