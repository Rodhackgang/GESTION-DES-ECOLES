<?php
include_once './liens_utiles/header.php';
include_once '../connexion/dbcon.php';
?>

<div class="content-body">
    <main id="gestion-matieres">
        <!-- Formulaire d'ajout de matière -->
        <?php
        // Vérifier s'il y a une erreur dans l'URL
        if (isset($_GET['error']) && !empty($_GET['error'])) {
            $error = $_GET['error'];

            // Afficher un message d'erreur approprié
            if ($error == "existe_deja") {
                echo "<p style='color: red;'>Le nom de la matière existe déjà.</p>";
            }
        }
        ?>

<div class="form-container">
    <h2>Ajouter une matière</h2>
    <form id="ajout-matiere-form" method="post" action="../Dashboard_Admin/ajout/ajout_matiere.php">
    <label for="nom">Nom de la matière :</label>
    <input type="text" id="nom" name="nom" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description" required></textarea>

    <label for="enseignant">Enseignant :</label>
    <select id="enseignant" name="enseignant" required>
        <?php
        $query_enseignants = "SELECT id, CONCAT(nom, ' ', prenom) AS nom_complet FROM staffs WHERE role = 'Enseignant'";
        $result_enseignants = mysqli_query($conn, $query_enseignants);

        // Affichage des options dans le menu déroulant pour les enseignants
        while ($row_enseignant = mysqli_fetch_assoc($result_enseignants)) {
            echo "<option value='" . $row_enseignant['id'] . "'>" . $row_enseignant['nom_complet'] . "</option>";
        }
        ?>
    </select>

    <label for="salle_classe">Salle de classe :</label>
    <select id="salle_classe" name="salle_classe" required>
        <?php
        $query_salles = "SELECT id, nom FROM salles_classe";
        $result_salles = mysqli_query($conn, $query_salles);

        // Affichage des options dans le menu déroulant pour les salles de classe
        while ($row_salle = mysqli_fetch_assoc($result_salles)) {
            echo "<option value='" . $row_salle['id'] . "'>" . $row_salle['nom'] . "</option>";
        }
        ?>
    </select>

    <button type="submit">Ajouter</button>
</form>


        <!-- Tableau des matières -->
        <div class="table-container">
    <h2>Liste des Matières</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Enseignant</th>
                <th>Classe</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Requête pour sélectionner les matières avec les détails de l'enseignant et de la salle de classe associés
            $sql = "SELECT matieres.id, matieres.nom, matieres.description, staffs.nom AS nom_enseignant, salles_classe.nom AS nom_salle 
                    FROM matieres 
                    LEFT JOIN staffs ON matieres.enseignant_id = staffs.id 
                    LEFT JOIN salles_classe ON matieres.classe_id = salles_classe.id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Affichage des données de chaque matière
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["nom"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td>" . $row["nom_enseignant"] . "</td>";
                    echo "<td>" . $row["nom_salle"] . "</td>";
                    echo "<td>
                            <button class='edit-btn'>Modifier</button>
                            <button class='delete-btn'>Supprimer</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Aucune matière trouvée</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>



    </main>

</div>
<?php
include_once './liens_utiles/footer.php';
?>
<script>
    // Fonction pour supprimer une matière
    function supprimerMatiere(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette matière ?")) {
            // Envoie d'une requête AJAX au serveur pour supprimer la matière
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Rafraîchir la page ou mettre à jour la liste des matières
                    location.reload();
                }
            };
            xhr.open("POST", "./../Dashboard_Admin/supprimer/supprimer_matiere.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("id=" + id);
        }
    }

    // Ajouter un événement de clic à chaque bouton "Supprimer"
    var deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
            supprimerMatiere(id);
        });
    });

    // Fonction pour charger les données d'une matière à modifier
    function chargerDonneesMatiere(id) {
        // Récupérer les données de la matière via une requête AJAX
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var matiere = JSON.parse(xhr.responseText);
                document.getElementById('nom').value = matiere.nom;
                document.getElementById('description').value = matiere.description;

                // Modifier le texte du bouton de soumission pour "Modifier"
                var submitBtn = document.querySelector('#ajout-matiere-form button[type="submit"]');
                submitBtn.textContent = "Modifier";
                // Mettre à jour l'action du formulaire pour modifier_matiere.php
                document.getElementById('ajout-matiere-form').action = "./../Dashboard_Admin/modifier/modifier_matieres.php";
                // Ajouter un champ caché pour transmettre l'ID de la matière
                document.getElementById('ajout-matiere-form').insertAdjacentHTML('beforeend', '<input type="hidden" name="id" value="' + id + '">');
            }
        };
        xhr.open("GET", "./../Dashboard_Admin/modifier/modifier_matiere.php?id=" + id, true);
        xhr.send();
    }

    // Ajouter un événement de clic à chaque bouton "Modifier"
    var editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.parentNode.parentNode.querySelector('td:first-child').textContent;
            chargerDonneesMatiere(id);
        });
    });

    // Fonction pour effacer les champs du formulaire
    function effacerChamps() {
        document.getElementById('nom').value = '';
        document.getElementById('description').value = '';

        // Modifier le texte du bouton de soumission pour "Ajouter"
        var submitBtn = document.querySelector('#ajout-matiere-form button[type="submit"]');
        submitBtn.textContent = "Ajouter";
        // Réinitialiser l'action du formulaire pour ajout_matiere.php
        document.getElementById('ajout-matiere-form').action = "./../Dashboard_Admin/ajout/ajout_matiere.php";
        // Supprimer le champ caché de l'ID de la matière s'il existe
        var idInput = document.querySelector('input[name="id"]');
        if (idInput) {
            idInput.parentNode.removeChild(idInput);
        }
    }

    // Appeler la fonction pour effacer les champs lorsque la page est chargée
    window.addEventListener('load', effacerChamps);
</script>

<style>
    /* Styles généraux */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
        /* Contraste légèrement amélioré */
    }

    /* Styles pour la section de gestion des matières */
    #gestion-matieres {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

   /* Style pour le formulaire */
.form-container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

/* Style pour les étiquettes */
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

/* Style pour les champs de texte et les zones de texte */
input[type="text"],
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* Pour inclure le padding et le bord dans la largeur totale */
}

/* Style pour le menu déroulant */
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    appearance: none; /* Pour supprimer les styles par défaut du navigateur */
    background-color: #fff;
    background-image: linear-gradient(45deg, transparent 50%, #ccc 50%),
        linear-gradient(135deg, #ccc 50%, transparent 50%);
    background-position: calc(100% - 20px) calc(1em + 2px),
        calc(100% - 15px) calc(1em + 2px);
    background-size: 5px 5px, 5px 5px;
    background-repeat: no-repeat;
}

/* Style pour le bouton */
button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #0056b3;
}

button:focus {
    outline: none;
}


    #ajout-matiere-form label {
        display: block;
        margin-bottom: 10px;
    }

    #ajout-matiere-form input[type="text"],
    #ajout-matiere-form textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    #ajout-matiere-form button[type="submit"] {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    #ajout-matiere-form button[type="submit"]:hover {
        background-color: #0056b3;
    }

    /* Styles pour le tableau des matières */
    .table-container {
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        background-color: #fff;
        max-width: 800px;
        width: 100%;
        overflow-x: auto;
    }

    .table-container h2 {
        margin-top: 0;
        margin-bottom: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        vertical-align: middle;
        /* Centrage vertical du texte */
    }

    th {
        background-color: #007bff;
        color: #fff;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .edit-btn,
    .delete-btn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 5px;
    }

    .edit-btn:hover,
    .delete-btn:hover {
        background-color: #0056b3;
    }
</style>