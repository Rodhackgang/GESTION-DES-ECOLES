<?php
include_once './liens_utiles/header.php';
// Inclure le fichier de configuration de la base de données
include_once "../connexion/dbcon.php";

// Récupérer les données de la table staffs
$query = "SELECT id, nom, prenom, role, telephone, email, date_embauche FROM staffs";
$result = $conn->query($query);

?>

<div class="content-body">
    <main id="gestion-personnel">
        <!-- Formulaire d'ajout de personnel -->
        <?php
        session_start();

        // Afficher le message s'il est présent
        if (isset($_SESSION['message'])) {
            echo "<p>{$_SESSION['message']}</p>";
            unset($_SESSION['message']); // Supprimer le message de la session
        }
        ?>

        <div class="form-container">
            <h2>Ajouter un membre du personnel</h2>
            <form id="ajout-personnel-form" method="POST" action="../Dashboard_Admin/ajout/ajout_personnel.php">
    <label for="nom_personnel">Nom:</label>
    <input type="text" id="nom_personnel" name="nom_personnel" required>

    <label for="prenom_personnel">Prénom:</label>
    <input type="text" id="prenom_personnel" name="prenom_personnel" required>

    <label for="role_personnel">Rôle:</label>
    <select id="role_personnel" name="role_personnel" required onchange="toggleClasseField()">
        <option value="enseignant">Enseignant</option>
        <option value="censeur">Censeur</option>
        <option value="surveillant">Surveillant</option>
        <option value="superviseur">Superviseur</option>
    </select>

    <div id="classe_field" style="display:none;">
        <label for="classe">Classe:</label>
        <select id="classe" name="classe_id">
            <!-- Options are populated dynamically -->
        </select>
    </div>

    <label for="responsabilites">Responsabilités:</label>
    <textarea id="responsabilites" name="responsabilites" required></textarea>

    <label for="horaires">Horaires:</label>
    <input type="text" id="horaires" name="horaires" required>

    <label for="disponibilite">Disponibilité:</label>
    <input type="text" id="disponibilite" name="disponibilite" required>

    <label for="telephone">Téléphone:</label>
    <input type="text" id="telephone" name="telephone" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="date_embauche">Date d'embauche:</label>
    <input type="date" id="date_embauche" name="date_embauche" required>

    <button type="submit">Ajouter</button>
</form>

<script>
    function toggleClasseField() {
        var roleSelect = document.getElementById("role_personnel");
        var classeField = document.getElementById("classe_field");

        if (roleSelect.value === "enseignant") {
            classeField.style.display = "block";
            populateClasseOptions();
        } else {
            classeField.style.display = "none";
        }
    }

    function populateClasseOptions() {
        var classeSelect = document.getElementById("classe");
        classeSelect.innerHTML = "";

        fetch('./getlistclasse.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(function(classe) {
                    var option = document.createElement("option");
                    option.text = classe.nom; // Assuming 'classe' is an object with 'nom' property
                    option.value = classe.id; // Assuming 'classe' has 'id' property
                    classeSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching classes:', error));
    }
</script>


        </div>



        <!-- Tableau du personnel -->
        <?php
        // Requête SQL pour récupérer les informations du personnel et le nom de la classe pour les enseignants
        $query = "SELECT s.id, s.nom, s.prenom, s.role, s.telephone, s.email, s.date_embauche, sc.nom AS classe_nom 
          FROM staffs s 
          LEFT JOIN salles_classe sc ON s.classe_id = sc.id";

        // Exécuter la requête
        $result = $conn->query($query);

        ?>

        <div class="table-container">
            <h2>Liste du Personnel</h2>
            <div id="tablecontainer">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Rôle</th>
                            <th>Classe</th> <!-- Ajouter une colonne pour la classe -->
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Date d'embauche</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            // Sortir les données de chaque ligne
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['prenom']}</td>
                            <td>{$row['role']}</td>";

                                // Afficher le nom de la classe si le rôle est enseignant
                                if ($row['role'] == 'Enseignant') {
                                    echo "<td>{$row['classe_nom']}</td>";
                                } else {
                                    echo "<td>-</td>"; // Afficher un tiret pour les autres rôles
                                }

                                echo "<td>{$row['telephone']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['date_embauche']}</td>
                            <td>
                                <button class='edit-btn'>Modifier</button>
                                <button class='delete-btn'>Supprimer</button>
                            </td>
                        </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>Aucun personnel trouvé</td></tr>"; // Mettre à jour le colspan pour inclure la nouvelle colonne
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


    </main>

</div>
<script>
    function supprimerPersonnel(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer ce membre du personnel ?")) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    location.reload();
                }
            };
            xhr.open("POST", "../Dashboard_Admin/supprimer/supprier_personnel.php", true);
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
                var personnel = JSON.parse(xhr.responseText);
                if (!personnel.error) {
                    document.getElementById('nom_personnel').value = personnel.nom;
                    document.getElementById('prenom_personnel').value = personnel.prenom;
                    document.getElementById('role_personnel').value = personnel.role;
                    document.getElementById('responsabilites').value = personnel.responsabilites;
                    document.getElementById('horaires').value = personnel.horaires;
                    document.getElementById('disponibilite').value = personnel.disponibilite;
                    document.getElementById('telephone').value = personnel.telephone;
                    document.getElementById('email').value = personnel.email;
                    document.getElementById('date_embauche').value = personnel.date_embauche;

                    // Modifier le texte du bouton de soumission pour "Modifier"
                    var submitBtn = document.querySelector('#ajout-personnel-form button[type="submit"]');
                    submitBtn.textContent = "Modifier";
                    // Mettre à jour l'action du formulaire pour modifier_personnel.php
                    document.getElementById('ajout-personnel-form').action = "../Dashboard_Admin/modifier/modifier_personnels.php";
                    // Ajouter un champ caché pour transmettre l'ID du personnel
                    if (!document.querySelector('input[name="id"]')) {
                        document.getElementById('ajout-personnel-form').insertAdjacentHTML('beforeend', '<input type="hidden" name="id" value="' + id + '">');
                    } else {
                        document.querySelector('input[name="id"]').value = id;
                    }
                } else {
                    alert(personnel.error);
                }
            }
        };
        xhr.open("GET", "../Dashboard_Admin/modifier/modifier_personnel.php?id=" + id, true);
        xhr.send();
    }

    // Ajouter un événement de clic à chaque bouton "Modifier"
    document.querySelectorAll('.edit-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
            chargerDonneesPersonnel(id);
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
        document.getElementById('ajout-personnel-form').action = "../Dashboard_Admin/ajout/ajout_personnel.php";
        // Supprimer le champ caché de l'ID du personnel s'il existe
        var idInput = document.querySelector('input[name="id"]');
        if (idInput) {
            idInput.parentNode.removeChild(idInput);
        }
    }

    // Appeler la fonction pour effacer les champs lorsque la page est chargée
    window.addEventListener('load', effacerChamps);
</script>

<style>
    /* Style pour le formulaire */
    .form-container {
        margin-left: 30px;
        background-color: #f9f9f9;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
        margin-bottom: 10px;
        color: #333;
    }

    .form-container label {
        display: block;
        margin-bottom: 5px;
        color: #666;
    }

    .form-container input[type="text"],
    .form-container input[type="tel"],
    .form-container input[type="email"],
    .form-container input[type="date"],
    .form-container select,
    .form-container textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
        box-sizing: border-box;
    }

    .form-container button[type="submit"] {
        background-color: #4caf50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .form-container button[type="submit"]:hover {
        background-color: #45a049;
    }

    /* Style pour le tableau */
    .table-container {
        margin-left: 30px;
        margin-bottom: 20px;
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }

    #tablecontainer {
        width: 100%;
        overflow-x: auto;
    }

    .table-container th,
    .table-container td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    .table-container th {
        background-color: #f2f2f2;
    }

    .table-container tbody tr:hover {
        background-color: #f9f9f9;
    }

    .table-container button {
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .table-container .edit-btn {
        background-color: #4caf50;
        color: white;

    }

    .table-container .delete-btn {
        background-color: #f44336;
        color: white;
        margin-top: 10px;
    }

    .table-container button:hover {
        opacity: 0.8;
    }
</style>


<?php
include_once './liens_utiles/footer.php';
?>