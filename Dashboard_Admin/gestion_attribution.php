<?php
include_once './liens_utiles/header.php';
include_once "../connexion/dbcon.php";
?>

<div class="content-body">
<div class="form-container">
            <h2>Attribution des Salles aux Cours et Examens</h2>
            <form id="attribution-salle-form" method="post" action="../Dashboard_Admin/ajout/ajout_attribution.php">
                <label for="cours">Cours/Examen:</label>
                <input type="text" id="cours" name="cours" required>

                <label for="salle">Salle:</label>
                <select id="salle" name="salle" required>
                    <option value="A1">Salle A1</option>
                    <option value="A2">Salle A2</option>
                    <!-- Ajoutez d'autres options de salle si nécessaire -->
                </select>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="heure_debut">Heure Début:</label>
                <input type="time" id="heure_debut" name="heure_debut" required>

                <label for="heure_fin">Heure Fin:</label>
                <input type="time" id="heure_fin" name="heure_fin" required>

                <button type="submit">Attribuer</button>
            </form>


        </div>
        <script>
            function supprimerSalle(id) {
                if (confirm("Êtes-vous sûr de vouloir supprimer cette salle de classe ?")) {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            location.reload();
                        }
                    };
                    xhr.open("POST", "../Dashboard_Admin/supprimer/supprimer_attribution.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send("id=" + id);
                }
            }

            function chargerDonneesSalle(id) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var salle = JSON.parse(xhr.responseText);
                        if (!salle.error) {
                            document.getElementById('cours').value = salle.cours;
                            document.getElementById('salle').value = salle.salle;
                            document.getElementById('date').value = salle.date;
                            document.getElementById('heure_debut').value = salle.heure_debut;
                            document.getElementById('heure_fin').value = salle.heure_fin;

                            // Modifier le texte du bouton de soumission pour "Modifier"
                            var submitBtn = document.querySelector('#attribution-salle-form button[type="submit"]');
                            submitBtn.textContent = "Modifier";
                            // Mettre à jour l'action du formulaire pour modifier_attribution.php
                            document.getElementById('attribution-salle-form').action = "../Dashboard_Admin/modifier/modifier_attributions.php";
                            // Ajouter un champ caché pour transmettre l'ID de l'attribution
                            if (!document.querySelector('input[name="id"]')) {
                                document.getElementById('attribution-salle-form').insertAdjacentHTML('beforeend', '<input type="hidden" name="id" value="' + id + '">');
                            } else {
                                document.querySelector('input[name="id"]').value = id;
                            }
                        } else {
                            alert(salle.error);
                        }
                    }
                };
                xhr.open("GET", "../Dashboard_Admin/modifier/modifier_attribution.php?id=" + id, true);
                xhr.send();
            }

            function modifierSalle(id) {
                chargerDonneesSalle(id);
            }

            function effacerChamps() {
                document.getElementById('cours').value = '';
                document.getElementById('salle').value = '';
                document.getElementById('date').value = '';
                document.getElementById('heure_debut').value = '';
                document.getElementById('heure_fin').value = '';

                // Modifier le texte du bouton de soumission pour "Ajouter"
                var submitBtn = document.querySelector('#attribution-salle-form button[type="submit"]');
                submitBtn.textContent = "Attribuer";
                // Réinitialiser l'action du formulaire pour ajout_attribution.php
                document.getElementById('attribution-salle-form').action = "../Dashboard_Admin/ajout/ajout_attribution.php";
                // Supprimer le champ caché de l'ID de l'attribution s'il existe
                var idInput = document.querySelector('input[name="id"]');
                if (idInput) {
                    idInput.parentNode.removeChild(idInput);
                }
            }

            // Appeler la fonction pour effacer les champs lorsque la page est chargée
            window.addEventListener('load', effacerChamps);
        </script>

        <!-- Suivi de l'occupation des salles -->
        <div class="table-container">
            <h2>Suivi de l'Occupation des Salles</h2>
            <table>
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Salle</th>
                        <th>Date</th>
                        <th>Heure Début</th>
                        <th>Heure Fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM attribution_salles";
                    $result = $conn->query($query);

                    // Vérifier s'il y a des données à afficher
                    if ($result->num_rows > 0) {
                        // Sortir les données de chaque ligne
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                <td>{$row['cours']}</td>
                <td>{$row['salle']}</td>
                <td>{$row['date']}</td>
                <td>{$row['heure_debut']}</td>
                <td>{$row['heure_fin']}</td>
                <td>
                    <button class='edit-btn' onclick='modifierSalle({$row['id']})'>Modifier</button>
                    <button class='delete-btn' onclick='supprimerSalle({$row['id']})'>Supprimer</button>
                </td>
            </tr>";
                        }
                    } else {
                        // Afficher un message si aucune salle de classe n'est trouvée
                        echo "<tr><td colspan='6'>Aucune salle de classe trouvée</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <?php

            // Requête pour récupérer les entretiens
            $sql = "SELECT salle, date_entretien, description FROM Entretien";
            $result = $conn->query($sql);

            // Vérification si des résultats ont été trouvés
            if ($result->num_rows > 0) {
                // Affichage des résultats
                while ($row = $result->fetch_assoc()) {
                    echo '<li>';
                    echo '<strong>Salle:</strong> ' . $row["salle"] . '<br>';
                    echo '<strong>Date:</strong> ' . $row["date_entretien"] . '<br>';
                    echo '<strong>Description:</strong> ' . $row["description"];
                    echo '</li>';
                }
            } else {
                echo "Aucun entretien trouvé";
            }

            ?>
        </div>
        <script>
            function supprimerSalle(id) {
                if (confirm("Êtes-vous sûr de vouloir supprimer cette salle de classe ?")) {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            location.reload();
                        }
                    };
                    xhr.open("POST", "../Dashboard_Admin/supprimer/supprimer_attribution.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send("id=" + id);
                }
            }

            function chargerDonneesSalle(id) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var salle = JSON.parse(xhr.responseText);
                        if (!salle.error) {
                            document.getElementById('cours').value = salle.cours;
                            document.getElementById('salle').value = salle.salle;
                            document.getElementById('date').value = salle.date;
                            document.getElementById('heure_debut').value = salle.heure_debut;
                            document.getElementById('heure_fin').value = salle.heure_fin;

                            // Modifier le texte du bouton de soumission pour "Modifier"
                            var submitBtn = document.querySelector('#attribution-salle-form button[type="submit"]');
                            submitBtn.textContent = "Modifier";
                            // Mettre à jour l'action du formulaire pour modifier_attributions.php
                            document.getElementById('attribution-salle-form').action = "../Dashboard_Admin/modifier/modifier_attributions.php";
                            // Ajouter un champ caché pour transmettre l'ID de l'attribution
                            if (!document.querySelector('input[name="id"]')) {
                                document.getElementById('attribution-salle-form').insertAdjacentHTML('beforeend', '<input type="hidden" name="id" value="' + id + '">');
                            } else {
                                document.querySelector('input[name="id"]').value = id;
                            }
                        } else {
                            alert(salle.error);
                        }
                    }
                };
                xhr.open("GET", "../Dashboard_Admin/modifier/modifier_attribution.php?id=" + id, true);
                xhr.send();
            }

            function modifierSalle(id) {
                chargerDonneesSalle(id);
            }

            function chargerDonneesSalle(id) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var salle = JSON.parse(xhr.responseText);
                        if (!salle.error) {
                            document.getElementById('cours').value = salle.cours;
                            document.getElementById('salle').value = salle.salle;
                            document.getElementById('date').value = salle.date;
                            document.getElementById('heure_debut').value = salle.heure_debut;
                            document.getElementById('heure_fin').value = salle.heure_fin;

                            // Modifier le texte du bouton de soumission pour "Modifier"
                            var submitBtn = document.querySelector('#attribution-salle-form button[type="submit"]');
                            submitBtn.textContent = "Modifier";
                            // Mettre à jour l'action du formulaire pour modifier_attribution.php
                            document.getElementById('attribution-salle-form').action = "../Dashboard_Admin/modifier/modifier_attributions.php";
                            // Ajouter un champ caché pour transmettre l'ID de l'attribution
                            if (!document.querySelector('input[name="id"]')) {
                                document.getElementById('attribution-salle-form').insertAdjacentHTML('beforeend', '<input type="hidden" name="id" value="' + id + '">');
                            } else {
                                document.querySelector('input[name="id"]').value = id;
                            }
                        } else {
                            alert(salle.error);
                        }
                    }
                };
                xhr.open("GET", "../Dashboard_Admin/modifier/modifier_attribution.php?id=" + id, true);
                xhr.send();
            }

            function modifierSalle(id) {
                chargerDonneesSalle(id);
            }

            function effacerChamps() {
                document.getElementById('cours').value = '';
                document.getElementById('salle').value = '';
                document.getElementById('date').value = '';
                document.getElementById('heure_debut').value = '';
                document.getElementById('heure_fin').value = '';

                // Modifier le texte du bouton de soumission pour "Ajouter"
                var submitBtn = document.querySelector('#attribution-salle-form button[type="submit"]');
                submitBtn.textContent = "Attribuer";
                // Réinitialiser l'action du formulaire pour ajout_attribution.php
                document.getElementById('attribution-salle-form').action = "../Dashboard_Admin/ajout/ajout_attribution.php";
                // Supprimer le champ caché de l'ID de l'attribution s'il existe
                var idInput = document.querySelector('input[name="id"]');
                if (idInput) {
                    idInput.parentNode.removeChild(idInput);
                }
            }

            // Appeler la fonction pour effacer les champs lorsque la page est chargée
            window.addEventListener('load', effacerChamps);

            function effacerChamps() {
                document.getElementById('cours').value = '';
                document.getElementById('salle').value = '';
                document.getElementById('date').value = '';
                document.getElementById('heure_debut').value = '';
                document.getElementById('heure_fin').value = '';

                // Modifier le texte du bouton de soumission pour "Ajouter"
                var submitBtn = document.querySelector('#attribution-salle-form button[type="submit"]');
                submitBtn.textContent = "Attribuer";
                // Réinitialiser l'action du formulaire pour ajout_attribution.php
                document.getElementById('attribution-salle-form').action = "../Dashboard_Admin/ajout/ajout_attribution.php";
                // Supprimer le champ caché de l'ID de l'attribution s'il existe
                var idInput = document.querySelector('input[name="id"]');
                if (idInput) {
                    idInput.parentNode.removeChild(idInput);
                }
            }

            // Appeler la fonction pour effacer les champs lorsque la page est chargée
            window.addEventListener('load', effacerChamps);
        </script>
</div>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f4f4f4;
        color: #333;
    }

    .planning-container {
        margin-top: 20px;
    }

    #planning-list {
        list-style-type: none;
        padding: 0;
    }

    #planning-list li {
        border: 1px solid #ccc;
        margin-bottom: 10px;
        padding: 10px;
        background-color: #f9f9f9;
    }

    #planning-list li strong {
        font-weight: bold;
    }

    #planning-list li:nth-child(even) {
        background-color: #e9e9e9;
    }

    main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #0056b3;
        margin-bottom: 20px;
    }

    .form-container,
    .table-container {
        margin-bottom: 40px;
    }

    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    input[type="time"],
    select,
    textarea {
        width: calc(100% - 22px);
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    textarea {
        resize: vertical;
        height: 100px;
    }

    button {
        padding: 10px 20px;
        background-color: #0056b3;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #004494;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #f8f8f8;
        color: #0056b3;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .button-container {
        display: flex;
        gap: 10px;
    }

    button.edit-btn {
        background-color: #ffc107;
    }

    button.edit-btn:hover {
        background-color: #e0a800;
    }

    button.delete-btn {
        background-color: #dc3545;
    }

    button.delete-btn:hover {
        background-color: #c82333;
    }

    .form-container,
    .planning-container {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    form label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    form select,
    form input,
    form textarea,
    form button {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    form button {
        background-color: #007BFF;
        color: #fff;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    form button:hover {
        background-color: #0056b3;
    }

    .planning-container ul {
        list-style-type: none;
        padding: 0;
    }

    .planning-container li {
        background-color: #f1f1f1;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 4px;
    }

    .planning-container li strong {
        display: block;
    }
</style>
<?php
include_once './liens_utiles/footer.php';
?>