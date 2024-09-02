<?php
include_once './liens_utiles/header.php';
include_once '../../connexion/dbcon.php';
?>
<div class="content-body">
    <main id="gestion-salles">
        <?php
        session_start();

        // Vérifier s'il y a une erreur dans l'URL
        if (isset($_GET['error']) && $_GET['error'] === 'exists') {
            // Afficher l'erreur récupérée de l'URL
            echo "<p style='color: red;'>Le nom de la salle de classe existe déjà.</p>";
        }

        // Afficher d'autres messages de session s'il y en a
        if (isset($_SESSION['message'])) {
            echo "<p>" . $_SESSION['message'] . "</p>";
            // Une fois affiché, supprimer le message de session pour qu'il ne s'affiche plus
            unset($_SESSION['message']);
        }
        ?>

        <!-- Formulaire d'ajout de salle de classe -->
        <div class="form-container">
            <h2>Ajouter une salle de classe</h2>
            <form id="ajout-salle-form" method="post" action="../../Dashboard_Staff/Enseigants/ajout/ajout_salles.php">
                <label for="nom">Nom de la salle:</label>
                <input type="text" id="nom" name="nom" required>

                <label for="capacite">Capacité:</label>
                <input type="number" id="capacite" name="capacite" required>

                <label for="prof_principal">Professeur Principal:</label>
                <input type="text" id="prof_principal" name="prof_principal" required>

                <button type="submit">Ajouter</button>
            </form>
        </div>

        <!-- Tableau des salles de classe -->
        <div class="table-container">
            <h2>Liste des Salles de Classe</h2>
            <table>

                <tbody>
                    <?php
                    $query = "SELECT * FROM salles_classe";
                    $result = $conn->query($query);

                    // Vérifier s'il y a des données à afficher
                    if ($result->num_rows > 0) {
                        // Sortir les données de chaque ligne
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['capacite']}</td>
                            <td>{$row['prof_principal']}</td>
                            <td>
                                <button class='edit-btn'>Modifier</button>
                                <button class='delete-btn'>Supprimer</button>
                            </td>
                        </tr>";
                        }
                    } else {
                        // Afficher un message si aucune salle de classe n'est trouvée
                        echo "<tr><td colspan='5'>Aucune salle de classe trouvée</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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
                    xhr.open("POST", "../../Dashboard_Staff/Enseigants/supprimer/supprimer_salle.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send("id=" + id);
                }
            }

            // Ajouter un événement de clic à chaque bouton "Supprimer"
            var deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
                    supprimerSalle(id);
                });
            });

            function chargerDonneesSalle(id) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var salle = JSON.parse(xhr.responseText);
                        if (!salle.error) {
                            document.getElementById('nom').value = salle.nom;
                            document.getElementById('capacite').value = salle.capacite;
                            document.getElementById('prof_principal').value = salle.prof_principal;

                            // Modifier le texte du bouton de soumission pour "Modifier"
                            var submitBtn = document.querySelector('#ajout-salle-form button[type="submit"]');
                            submitBtn.textContent = "Modifier";
                            // Mettre à jour l'action du formulaire pour modifier_salle.php
                            document.getElementById('ajout-salle-form').action = "../../Dashboard_Staff/Enseigants/modifier/modifier_salles.php";
                            // Ajouter un champ caché pour transmettre l'ID de la salle
                            if (!document.querySelector('input[name="id"]')) {
                                document.getElementById('ajout-salle-form').insertAdjacentHTML('beforeend', '<input type="hidden" name="id" value="' + id + '">');
                            } else {
                                document.querySelector('input[name="id"]').value = id;
                            }
                        } else {
                            alert(salle.error);
                        }
                    }
                };
                xhr.open("GET", "../../Dashboard_Staff/Enseigants/modifier/modifier_salle.php?id=" + id, true);
                xhr.send();
            }

            // Ajouter un événement de clic à chaque bouton "Modifier"
            document.querySelectorAll('.edit-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
                    chargerDonneesSalle(id);
                });
            });

            // Fonction pour effacer les champs du formulaire
            function effacerChamps() {
                document.getElementById('nom').value = '';
                document.getElementById('capacite').value = '';
                document.getElementById('prof_principal').value = '';

                // Modifier le texte du bouton de soumission pour "Ajouter"
                var submitBtn = document.querySelector('#ajout-salle-form button[type="submit"]');
                submitBtn.textContent = "Ajouter";
                // Réinitialiser l'action du formulaire pour ajout_salle.php
                document.getElementById('ajout-salle-form').action = "../../Dashboard_Staff/Enseigants/ajout/ajout_salles.php";
                // Supprimer le champ caché de l'ID de la salle s'il existe
                var idInput = document.querySelector('input[name="id"]');
                if (idInput) {
                    idInput.parentNode.removeChild(idInput);
                }
            }

            // Appeler la fonction pour effacer les champs lorsque la page est chargée
            window.addEventListener('load', effacerChamps);
        </script>
    </main>


</div>
<?php
include_once './liens_utiles/footer.php';
?>
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