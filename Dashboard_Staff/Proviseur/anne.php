<?php
    include_once "../../Dashboard_Staff/Proviseur/liens_utiles/header.php";
    // Connexion à la base de données
    include('../../connexion/dbcon.php');
?>

<div class="content-body">
        <div class="container">
            <h1>Gestion des Années Scolaires</h1>
            <form id="ajout-annee-scolaire-form" method="POST" action="../../../Dashboard_Staff/Proviseur/ajout/ajout_anne.php">
                <div class="form-group">
                    <label for="annee_debut">Année de début:</label>
                    <input type="number" id="annee_debut" name="annee_debut" required>
                </div>
                <div class="form-group">
                    <label for="annee_fin">Année de fin:</label>
                    <input type="number" id="annee_fin" name="annee_fin" required>
                </div>
                <button type="submit" name="action" value="ajouter">Ajouter</button>
            </form>

            <h2>Liste des Années Scolaires</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Année de début</th>
                        <th>Année de fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                   $result = $conn->query("SELECT * FROM annees_scolaires");
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['annee_debut'] . "</td>";
                            echo "<td>" . $row['annee_fin'] . "</td>";
                            echo "<td>";
                            echo "<form method='POST' action='../../../Dashboard_Staff/Proviseur/ajout/ajout_anne.php' class='inline-form'>";
                            echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                            echo "<input type='number' name='annee_debut' value='" . $row['annee_debut'] . "' required>";
                            echo "<input type='number' name='annee_fin' value='" . $row['annee_fin'] . "' required>";
                            echo "<button type='submit' name='action' value='supprimer'>Supprimer</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<style>
 
.container {
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1, h2 {
    text-align: center;
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

input[type="number"], input[type="text"], input[type="date"], textarea, select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    color: #333;
}

button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

thead {
    background-color: #007bff;
    color: white;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #e9e9e9;
}

.inline-form {
    display: inline-block;
}

.inline-form input {
    width: 80px;
    padding: 5px;
    margin-right: 5px;
    font-size: 14px;
}

.inline-form button {
    margin-right: 5px;
    font-size: 14px;
}

</style>
</div>
<?php
    include_once "../../Dashboard_Staff/Proviseur/liens_utiles/footer.php"
?>