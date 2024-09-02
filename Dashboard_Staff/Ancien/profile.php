<?php
session_start();
include_once '../Dashboard_Staff/liens_utiles/header.php';
include_once '../connexion/dbcon.php';

if ($_SESSION['role'] === 'Staff') {
    $email = $_SESSION['email'];

    // Requête SQL pour récupérer les informations du staff à partir de son email
    $sql = "SELECT * FROM staffs WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
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
                    <span class="label">Role :</span>
                    <span class="value"><?php echo $row['role']; ?></span>
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
                    <span class="label">Date d'embauche :</span>
                    <span class="value"><?php echo $row['date_embauche']; ?></span>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "Erreur: Vos informations ne sont pas disponibles.";
    }
} else {
    header('Location: ../index.php');
    exit();
}

include_once '../Dashboard_Staff/liens_utiles/footer.php';
?>
