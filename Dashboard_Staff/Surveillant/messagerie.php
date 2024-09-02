<?php
session_start();
// Inclure le fichier d'en-tête
include_once './liens_utiles/header.php';
// Inclure le fichier de connexion à la base de données
include_once '../../connexion/dbcon.php';

// Début du corps du contenu de la page
echo '<div class="content-body">';
echo '<main id="gestion-messagerie">';

// Affichage des messages (erreurs ou succès) si présents dans l'URL
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    if (strpos($message, "erreur") !== false) {
        echo "<p style='color: red;'>Erreur : " . $message . "</p>";
    } elseif (strpos($message, "succès") !== false) {
        echo "<p style='color: green;'>" . $message . "</p>";
    } else {
        echo "<p>" . $message . "</p>";
    }
}


echo '<div class="table-container">';
echo '<h2>Messages Récues</h2>';
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>ID Expéditeur</th>';
echo '<th>ID Destinataire</th>';
echo '<th>Message</th>';
echo '<th>Date d\'envoi</th>';

echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Requête SQL pour récupérer les messages envoyés par l'admin
$sql_received_messages = "SELECT * FROM messages WHERE expediteur_id != ?";
$stmt_received_messages = mysqli_prepare($conn, $sql_received_messages);
$expediteur_id = 2; // L'ID de l'expéditeur à exclure
mysqli_stmt_bind_param($stmt_received_messages, "i", $expediteur_id); // "i" pour un seul paramètre de type integer
mysqli_stmt_execute($stmt_received_messages);
$result_received_messages = mysqli_stmt_get_result($stmt_received_messages);

// Affichage des messages envoyés dans la table
if (mysqli_num_rows($result_received_messages) > 0) {
    while ($row_received_message = mysqli_fetch_assoc($result_received_messages)) {
        echo '<tr>';
        echo '<td>' . $row_received_message['id'] . '</td>';
        echo '<td>' . $row_received_message['expediteur_id'] . '</td>';
        echo '<td>' .$row_received_message['destinataire_id'] . '</td>';
        echo '<td>' . $row_received_message['message'] . '</td>';
        echo '<td>' .  $row_received_message['date_envoi'] . '</td>';
        
        // Vous pouvez ajouter d'autres colonnes si nécessaire
        echo '</tr>';
    }
} else {
    echo '<tr>';
    echo '<td colspan="6">Aucun message avec un expéditeur différent de 2 trouvé.</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';



// Fermer la connexion à la base de données
mysqli_close($conn);
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