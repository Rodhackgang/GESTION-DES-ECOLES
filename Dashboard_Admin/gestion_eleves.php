<?php
include_once './liens_utiles/header.php';
include_once './../connexion/dbcon.php';
?>
<div class="content-body">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        section {
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        h2 {
            color: #333;
        }

        form {
            display: grid;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="date"],
        input[type="tel"],
        input[type="email"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="tel"]:focus,
        input[type="email"]:focus,
        select:focus {
            border-color: #007bff;
        }

        button {
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        .edit-btn,
        .delete-btn {
            padding: 8px 16px;
            margin-top: 5px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .edit-btn:hover,
        .delete-btn:hover {
            background-color: #218838;
        }

        #tablecontainer {
            overflow-x: auto;
            width: 100%;
        }
    </style>


    <section id="gestion-eleves">
        <h2>Gestion des Élèves</h2>


        <!-- Formulaire d'ajout d'élève -->
        <div class="form-container">
    <h3>Ajouter un élève</h3>
    <form id="ajout-eleve-form" method="POST" action="../Dashboard_Admin/ajout/ajout_eleves.php">
        <!-- Les champs du formulaire restent les mêmes -->
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="date_naissance">Date de Naissance:</label>
        <input type="date" id="date_naissance" name="date_naissance" required>

        <label for="adresse">Adresse:</label>
        <input type="text" id="adresse" name="adresse" required>

        <label for="telephone">Téléphone:</label>
        <input type="tel" id="telephone" name="telephone" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="date_inscription">Date d'Inscription:</label>
        <input type="date" id="date_inscription" name="date_inscription" required>

        <label for="annee_scolaire">Année Scolaire :</label>
        <select id="annee_scolaire" name="annee_scolaire" required>
            <?php
            $sql = "SELECT * FROM annees_scolaires";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['annee_debut'] . "-" . $row['annee_fin'] . "</option>";
                }
            }
            ?>
        </select>

        <!-- Nouveau champ de sélection pour la classe -->
        <label for="classe">Classe :</label>
        <select id="classe" name="classe" required>
            <?php
            $sql = "SELECT * FROM salles_classe";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                }
            }
            ?>
        </select>

        <button type="submit">Ajouter</button>
    </form>
</div>


        <!-- Tableau des élèves -->
        <h3>Liste des élèves</h3>
        <div id="tablecontainer">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de Naissance</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Date d'Inscription</th>
                        <th>Année</th>
                        <th>Classe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT e.id, e.nom, e.prenom, e.date_naissance, e.adresse, e.telephone, e.email, e.date_inscription, 
                           a.annee_debut, a.annee_fin, c.nom AS classe_nom 
                    FROM eleves e
                    JOIN annees_scolaires a ON e.annee_scolaire_id = a.id
                    JOIN salles_classe c ON e.classe_id = c.id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["nom"] . "</td>";
                            echo "<td>" . $row["prenom"] . "</td>";
                            echo "<td>" . $row["date_naissance"] . "</td>";
                            echo "<td>" . $row["adresse"] . "</td>";
                            echo "<td>" . $row["telephone"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["date_inscription"] . "</td>";
                            echo "<td>" . $row["annee_debut"] . "-" . $row["annee_fin"] . "</td>";
                            echo "<td>" . $row["classe_nom"] . "</td>";
                            echo "<td>
                        <button class='edit-btn'>Modifier</button>
                        <button class='delete-btn'>Supprimer</button>
                      </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11'>Aucun élève trouvé</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </section>
    <script>
        // Fonction pour supprimer un élève
        function supprimerEleve(id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cet élève ?")) {
                // Envoie d'une requête AJAX au serveur pour supprimer l'élève
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Rafraîchir la page ou mettre à jour la liste des élèves
                        location.reload();
                    }
                };
                xhr.open("POST", "../Dashboard_Admin/supprimer/supprimer.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("id=" + id);
            }
        }

        // Ajouter un événement de clic à chaque bouton "Supprimer"
        var deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
                supprimerEleve(id);
            });
        });


        function chargerDonneesEleve(id) {
            // Récupérer les données de l'élève via une requête AJAX
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var eleve = JSON.parse(xhr.responseText);
                    document.getElementById('nom').value = eleve.nom;
                    document.getElementById('prenom').value = eleve.prenom;
                    document.getElementById('date_naissance').value = eleve.date_naissance;
                    document.getElementById('adresse').value = eleve.adresse;
                    document.getElementById('telephone').value = eleve.telephone;
                    document.getElementById('email').value = eleve.email;
                    document.getElementById('date_inscription').value = eleve.date_inscription;

                    // Modifier le texte du bouton de soumission pour "Modifier"
                    var submitBtn = document.querySelector('#ajout-eleve-form button[type="submit"]');
                    submitBtn.textContent = "Modifier";
                    // Mettre à jour l'action du formulaire pour modifier_eleve.php
                    document.getElementById('ajout-eleve-form').action = "./../Dashboard_Admin/modifier/modifier_eleves.php";
                    // Ajouter un champ caché pour transmettre l'ID de l'élève
                    document.getElementById('ajout-eleve-form').insertAdjacentHTML('beforeend', '<input type="hidden" name="id" value="' + id + '">');
                }
            };
            xhr.open("GET", "./../Dashboard_Admin/modifier/modifier_eleve.php?id=" + id, true);
            xhr.send();
        }

        // Ajouter un événement de clic à chaque bouton "Modifier"
        var editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
                chargerDonneesEleve(id);
            });
        });

        // Fonction pour effacer les champs du formulaire
        function effacerChamps() {
            document.getElementById('nom').value = '';
            document.getElementById('prenom').value = '';
            document.getElementById('date_naissance').value = '';
            document.getElementById('adresse').value = '';
            document.getElementById('telephone').value = '';
            document.getElementById('email').value = '';
            document.getElementById('date_inscription').value = '';

            // Modifier le texte du bouton de soumission pour "Ajouter"
            var submitBtn = document.querySelector('#ajout-eleve-form button[type="submit"]');
            submitBtn.textContent = "Ajouter";
            // Réinitialiser l'action du formulaire pour ajout_eleves.php
            document.getElementById('ajout-eleve-form').action = "./../Dashboard_Admin/ajout/ajout_eleves.php";
            // Supprimer le champ caché de l'ID de l'élève s'il existe
            var idInput = document.querySelector('input[name="id"]');
            if (idInput) {
                idInput.parentNode.removeChild(idInput);
            }
        }

        // Appeler la fonction pour effacer les champs lorsque la page est chargée
        window.addEventListener('load', effacerChamps);
    </script>



    <?php
    // Vérification de la présence de messages de succès ou d'erreur
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        $message = "Inscription réussie.";
    } elseif (isset($_GET['error'])) {
        $message = "Erreur lors de l'inscription: " . urldecode($_GET['error']);
    } else {
        $message = ""; // Aucun message par défaut
    }
    ?>

    <?php
    include_once './liens_utiles/footer.php';
    ?>