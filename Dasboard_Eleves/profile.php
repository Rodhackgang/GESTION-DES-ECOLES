<?php
session_start();
include_once '../Dasboard_Eleves/liens_utiles/header.php';
include_once '../connexion/dbcon.php'; // Inclure le fichier de connexion à la base de données
// Vérifier si l'utilisateur est connecté en tant qu'élève

if ($_SESSION['role'] === 'Eleve') {
    $email = $_SESSION['email']; // Récupérer l'email de l'utilisateur connecté

    // Requête SQL pour récupérer les informations de l'élève à partir de son email
    $sql = "SELECT * FROM eleves WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // L'utilisateur est un élève et ses informations existent dans la table eleves
        $row = $result->fetch_assoc();
        // Afficher les informations de l'élève
        ?>
        <style>
            .container {
                background-color: #fff;
                border-radius: 20px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
                max-width: 500px;
                width: 100%;
            }
            .info {
                margin-bottom: 10px;
            }
            .label {
                font-weight: bold;
            }
            .value {
                color: #333;
            }
        </style>
        <div class="content-body">
            <div class="container">
                <div class="info">
                    <span class="label">Nom :</span>
                    <span class="value"><?php echo $row['nom']; ?></span>
                </div>
                <div class="info">
                    <span class="label">Prénom :</span>
                    <span class="value"><?php echo $row['prenom']; ?></span>
                </div>
                <div class="info">
                    <span class="label">Date de naissance :</span>
                    <span class="value"><?php echo $row['date_naissance']; ?></span>
                </div>
                <div class="info">
                    <span class="label">Adresse :</span>
                    <span class="value"><?php echo $row['adresse']; ?></span>
                </div>
                <div class="info">
                    <span class="label">Téléphone :</span>
                    <span class="value"><?php echo $row['telephone']; ?></span>
                </div>
                <div class="info">
                    <span class="label">Email :</span>
                    <span class="value"><?php echo $row['email']; ?></span>
                </div>
                <div class="info">
                    <span class="label">Date d'inscription :</span>
                    <span class="value"><?php echo $row['date_inscription']; ?></span>
                </div>
            </div>
        </div>
        <?php
    } else {
        // L'utilisateur est un élève mais ses informations n'existent pas dans la table eleves
        echo "Vous n'avez pas payé votre scolarité. Veuillez le faire.";
    }
} else {
    // Rediriger vers la page d'accueil si l'utilisateur n'est pas connecté en tant qu'élève
    header('Location: ../index.php');
    exit();
}

include_once '../Dasboard_Eleves/liens_utiles/footer.php';
?>
