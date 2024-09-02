<?php
session_start();
include_once './liens_utiles/header.php';
include_once '../connexion/dbcon.php';
?>

<div class="content-body">
<div class="container">
        

    <section id="gestion-eleves">

    <h1>Liste des Staffs</h1>
        <table>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Rôle</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Date d'embauche</th>
            </tr>
            <?php
                // Requête SQL pour récupérer les informations sur les staffs
                $sql = "SELECT * FROM staffs";
                $result = $conn->query($sql);

                // Afficher les données dans un tableau HTML
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['nom']."</td>";
                        echo "<td>".$row['prenom']."</td>";
                        echo "<td>".$row['role']."</td>";
                        echo "<td>".$row['telephone']."</td>";
                        echo "<td>".$row['email']."</td>";
                        echo "<td>".$row['date_embauche']."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "0 results";
                }
                
            ?>
        </table> <br>
        <h2>Gestion des Élèves</h2>


        <!-- Formulaire d'ajout d'élève -->
        <div class="form-container">
            <h3>Ajouter un élève</h3>
            <form id="ajout-eleve-form" method="POST" action="../Dashboard_Staff/ajout/ajout_eleve.php">
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
                            // Affichez chaque année scolaire comme une option dans le select
                            echo "<option value='" . $row['id'] . "'>" . $row['annee_debut'] . "-" . $row['annee_fin'] . "</option>";
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, nom, prenom, date_naissance, adresse, telephone, email, date_inscription FROM eleves";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Affichage des données de chaque élève
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
                        echo "<td>
                            <button class='edit-btn'>Modifier</button>
                          </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Aucun élève trouvé</td></tr>";
                }

                // Fermeture de la connexion
                
                ?>
            </tbody>
        </table>
        </div>
    </section>
    <script>
       
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
                    document.getElementById('ajout-eleve-form').action = "./../Dashboard_Staff/modifier/modifier_eleves.php";
                    // Ajouter un champ caché pour transmettre l'ID de l'élève
                    document.getElementById('ajout-eleve-form').insertAdjacentHTML('beforeend', '<input type="hidden" name="id" value="' + id + '">');
                }
            };
            xhr.open("GET", "./../Dashboard_Staff/modifier/modifier_eleve.php?id=" + id, true);
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
</div>
<style>
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
        #tablecontainer{
            overflow-x: auto;
            width: 100%;
        }
</style>
<?php
include_once './liens_utiles/footer.php';
?>