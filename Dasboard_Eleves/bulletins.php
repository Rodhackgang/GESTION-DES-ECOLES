<?php
session_start();
include_once '../Dasboard_Eleves/liens_utiles/header.php';
include_once '../connexion/dbcon.php'; // Inclure le fichier de connexion à la base de données

// Vérifier si l'utilisateur est connecté en tant qu'élève
if ($_SESSION['role'] === 'Eleve') {
    $email = $_SESSION['email']; // Récupérer l'email de l'utilisateur connecté

    // Requête SQL pour récupérer l'ID de l'élève à partir de son email
    $sql = "SELECT id FROM eleves WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $eleve_id = $row['id'];
        } else {
            // L'utilisateur est un élève mais ses informations n'existent pas dans la table eleves
            echo "Erreur: Impossible de trouver l'élève dans la base de données.";
            exit();
        }

        $stmt->close();
    } else {
        // Erreur de préparation de la requête SQL
        echo "Erreur: Impossible de préparer la requête SQL.";
        exit();
    }

    // Requête SQL pour récupérer le bulletin de l'élève connecté
    $query = "SELECT bulletins.id, eleves.nom, eleves.prenom, CONCAT(annees_scolaires.annee_debut, '-', annees_scolaires.annee_fin) AS annee, bulletins.semestre, bulletins.commentaires, bulletins.eleve_id 
              FROM bulletins 
              JOIN eleves ON bulletins.eleve_id = eleves.id 
              JOIN annees_scolaires ON bulletins.annee_scolaire_id = annees_scolaires.id
              WHERE bulletins.eleve_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $eleve_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<div class="content-body">';
            echo '<main id="gestion-bulletins">';
            echo '<div class="table-container">';
            echo '<h2>Votre Bulletin de Notes</h2>';
            echo '<table border="1">';
            echo '<tr><th>ID de l\'élève</th><th>Nom de l\'élève</th><th>Année scolaire</th><th>Semestre</th><th>Actions</th></tr>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['eleve_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['prenom']) . '</td>';
                echo '<td>' . htmlspecialchars($row['annee']) . '</td>';
                echo '<td>' . htmlspecialchars($row['semestre']) . '</td>';
                echo '<td>
                        <a href="./page_bulletins.php?id=' . htmlspecialchars($row['id']) . '">Imprimer</a>
                      </td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div>';
            echo '</main>';
            echo '</div>';
        } else {
            echo "Aucun bulletin trouvé pour cet élève.";
        }

        $stmt->close();
    } else {
        echo "Erreur: Impossible de préparer la requête SQL pour récupérer les bulletins de l'élève.";
    }
} else {
    // Rediriger vers la page d'accueil si l'utilisateur n'est pas connecté en tant qu'élève
    header('Location: ../index.php');
    exit();
}

include_once '../Dasboard_Eleves/liens_utiles/footer.php';
?>

<style>
    /* styles.css */

    #gestion-bulletins {
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
    form textarea,
    form select {
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
    .delete-btn,
    .imprim-btn {
        padding: 5px 10px;
        margin: 2px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
</style>
