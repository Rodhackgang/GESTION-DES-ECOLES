<?php
include_once './liens_utiles/header.php';
include_once '../../connexion/dbcon.php';
?>

<div class="content-body">
    <main id="gestion-emplois">
        <!-- Formulaire d'ajout d'emploi du temps -->
        <div class="form-container">
            <h2>Ajouter un emploi du temps</h2>
            <?php
            if (isset($_GET['error'])) {
                echo "<p style='color: red;'>Erreur : " . htmlspecialchars($_GET['error']) . "</p>";
            }

            if (isset($_GET['success'])) {
                echo "<p style='color: green;'>Emploi du temps ajouté avec succès.</p>";
            }
            ?>
            <form id="ajout-emploi-form" method="POST" action='.././Enseigants/ajout/ajout_emplois.php'>
            <label for="classe-id">Classe:</label>
            <select id="classe-id" name="classe-id" required>
                <?php
               

                // Récupérer la liste des classes
                $sql = "SELECT id, nom FROM salles_classe";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['nom'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Aucune classe trouvée</option>";
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
            <br>

            <label for="staff-id">Personnel:</label>
            <select id="staff-id" name="staff-id" required>
                <?php
                // Récupérer la liste des personnels
                $sql = "SELECT id, nom, prenom FROM staffs";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['nom'] . " " . $row['prenom'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Aucun personnel trouvé</option>";
                }
               
                ?>
            </select>
            <br>

            <label for="jour">Jour:</label>
            <select id="jour" name="jour" required>
                <option value="Lundi">Lundi</option>
                <option value="Mardi">Mardi</option>
                <option value="Mercredi">Mercredi</option>
                <option value="Jeudi">Jeudi</option>
                <option value="Vendredi">Vendredi</option>
                <option value="Samedi">Samedi</option>
            </select>
            <br>

            <label for="heure-debut">Heure de début:</label>
            <input type="time" id="heure-debut" name="heure-debut" required>
            <br>

            <label for="heure-fin">Heure de fin:</label>
            <input type="time" id="heure-fin" name="heure-fin" required>
            <br>

            <button type="submit">Ajouter</button>
        </form>
        </div>

        <!-- Tableau des emplois du temps -->
        <table>
            <?php

            // Récupérer les emplois du temps depuis la base de données
            $sql = "SELECT * FROM emplois_temps";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<div class="table-container">
                <h2>Liste des Emplois du Temps</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Classe</th>
                            <th>ID Matière</th>
                            <th>ID Personnel</th>
                            <th>Jour</th>
                            <th>Heure de début</th>
                            <th>Heure de fin</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>';
                // Afficher chaque emploi du temps
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>
                    <td>' . $row["id"] . '</td>
                    <td>' . $row["classe_id"] . '</td>
                    <td>' . $row["matiere_id"] . '</td>
                    <td>' . $row["staff_id"] . '</td>
                    <td>' . $row["jour"] . '</td>
                    <td>' . $row["heure_debut"] . '</td>
                    <td>' . $row["heure_fin"] . '</td>
                    <td>
                        <button class="delete-btn" onclick="deleteEmploi(' . $row["id"] . ')">Supprimer</button>
                    </td>
                </tr>';
                }
                echo '    </tbody>
                </table>
            </div>'; // Balise fermante ajoutée ici
            } else {
                echo '<p>Aucun emploi du temps trouvé.</p>';
            }

            // Fermer la connexion
            $conn->close();
            ?>
        </table>

        <div class="content-body">
    <!-- Formulaire de gestion des remplacements -->
    <div class="form-container">
        <h2>Gestion des remplacements</h2>
        <form id="remplacement-form" method="POST" action="../Dashboard_Admin/modifier/modifier_emplois.php">
            <label for="remplace-id">ID de l'emploi à remplacer:</label>
            <input type="number" id="remplace-id" name="remplace-id" required>

            <label for="nouveau-staff-id">ID du nouveau personnel:</label>
            <input type="number" id="nouveau-staff-id" name="nouveau-staff-id" required>

            <label for="nouvelle-heure-debut">Nouvelle heure de début:</label>
            <input type="time" id="nouvelle-heure-debut" name="nouvelle-heure-debut">

            <label for="nouvelle-heure-fin">Nouvelle heure de fin:</label>
            <input type="time" id="nouvelle-heure-fin" name="nouvelle-heure-fin">

            <button type="submit">Remplacer</button>
        </form>
    </div>
</div>

    </main>
</div>
<script>
    // Fonction pour envoyer une requête de suppression au serveur
    function deleteEmploi(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cet emploi du temps ?")) {
            // Envoyer une requête Ajax
            var xhr = new XMLHttpRequest();
            xhr.open("POST", ".././Enseigants/supprimer/supprimer_emplois.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Gérer la réponse du serveur ici
                    var response = xhr.responseText;
                    if (response === "success") {
                        // Rafraîchir la page ou effectuer d'autres actions
                        window.location.reload();
                    } else {
                        alert("Erreur lors de la suppression de l'emploi du temps.");
                    }
                }
            };
            xhr.send("id=" + id);
        }
    }
</script>
<style>
    /* styles.css */


    #gestion-emplois {
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
    form select {
        padding: 8px;
        margin-top: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
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