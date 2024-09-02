<?php
session_start();
include_once '../Dasboard_Eleves/liens_utiles/header.php';
?>

<div class="content-body">
<?php
include_once '../connexion/dbcon.php';

// Vérifie si l'adresse e-mail de l'élève est définie dans la session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Recherche de l'élève par son adresse e-mail
    $query = "SELECT id, nom, prenom FROM eleves WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Récupérer les informations de l'élève
        $row = $result->fetch_assoc();
        $eleve_id = $row['id'];

        // Requête SQL pour récupérer les messages envoyés par les administrateurs à l'élève connecté
        $sql_messages = "SELECT messages.id, messages.expediteur_id, messages.destinataire_id, messages.message, messages.date_envoi, utilisateurs.nom_utilisateur AS expediteur_nom
                        FROM messages
                        INNER JOIN utilisateurs ON messages.expediteur_id = utilisateurs.id
                        WHERE utilisateurs.role = 'Admin' AND messages.destinataire_id = ?";
        $stmt_messages = mysqli_prepare($conn, $sql_messages);
        mysqli_stmt_bind_param($stmt_messages, "i", $eleve_id); // "i" pour un seul paramètre de type integer
        mysqli_stmt_execute($stmt_messages);
        $result_messages = mysqli_stmt_get_result($stmt_messages);

        // Affichage des messages envoyés dans la table
        if (mysqli_num_rows($result_messages) > 0) {
            echo '<div class="table-container">';
            echo '<h2>Messages Envoyés par les Administrateurs</h2>';
            echo '<table class="message-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Expéditeur</th>';
            echo '<th>Message</th>';
            echo '<th>Date d\'envoi</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            while ($row_message = mysqli_fetch_assoc($result_messages)) {
                echo '<tr>';
                echo '<td>' . $row_message['id'] . '</td>';
                echo '<td>' . $row_message['expediteur_nom'] . '</td>';
                echo '<td>' . $row_message['message'] . '</td>';
                echo '<td>' . $row_message['date_envoi'] . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<p>Aucun message envoyé par un administrateur à cet élève.</p>';
        }

        // Fermer la connexion à la base de données
        mysqli_stmt_close($stmt_messages);
    } else {
        echo "Aucun élève trouvé avec cette adresse e-mail.";
    }
} else {
    echo "L'adresse e-mail de l'élève n'est pas définie dans la session.";
}
?>

</div>

<?php
include_once '../Dasboard_Eleves/liens_utiles/footer.php';
?>


<style>
    /* styles.css */

    #gestion-messagerie {
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

    .delete-btn {
        padding: 5px 10px;
        margin: 2px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background-color: #dc3545;
        color: #fff;
        transition: background-color 0.3s ease;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

    .message-table {
        width: 100%;
        border-collapse: collapse;
    }

    .message-table th,
    .message-table td {
        padding: 10px;
        border-bottom: 1px solid #ccc;
        text-align: left;
    }

    .message-table th {
        background-color: #f0f0f0;
    }

    .message-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .message-table tbody tr:hover {
        background-color: #e0e0e0;
    }

    /* Style pour les boutons de suppression */
    .delete-btn {
        padding: 5px 10px;
        margin: 2px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background-color: #dc3545;
        color: #fff;
        transition: background-color 0.3s ease;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }
</style>


<?php
include_once './liens_utiles/footer.php';
?>