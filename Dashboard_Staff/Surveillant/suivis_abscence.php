<?php
include_once './liens_utiles/header.php';
include_once '../../connexion/dbcon.php';
?>
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


    <div class="content-body">
        <main id="suivi-absences">
            <!-- Formulaire d'ajout d'absence -->
            <!-- Affichage des messages -->
            <?php
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
                <form id="ajout-absence-form" method="POST" action="../../Dashboard_Staff/Surveillant/ajout/ajout_abscence.php">
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

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['eleve_id']}</td>
                                <td>{$row['date_absence']}</td>
                                <td>" . htmlspecialchars($row['motif']) . "</td>
                                <td>
                                    <button class='delete-btn' onclick='supprimerAbsence({$row['id']})'>Supprimer</button>
                                </td>
                            </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Aucune absence trouvée</td></tr>";
                        }
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
                        if (xhr.responseText.trim() === "success") {
                            location.reload();
                        } else {
                            alert("Erreur lors de la suppression de l'absence.");
                        }
                    }
                };
                xhr.open("POST", "../../Dashboard_Staff/Surveillant/supprimer/supprimer_absence.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("id=" + id);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var id = this.closest('tr').querySelector('td:first-child').textContent;
                    supprimerAbsence(id);
                });
            });
        });
    </script>
<?php
include_once './liens_utiles/footer.php';
?>