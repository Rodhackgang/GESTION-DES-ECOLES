<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si toutes les données nécessaires sont présentes dans la requête POST
    if (isset($_POST['id']) && isset($_POST['nom_personnel']) && isset($_POST['prenom_personnel']) && isset($_POST['role_personnel']) && isset($_POST['responsabilites']) && isset($_POST['horaires']) && isset($_POST['disponibilite']) && isset($_POST['telephone']) && isset($_POST['email']) && isset($_POST['date_embauche'])) {
        // Connexion à la base de données
        include_once './../../connexion/dbcon.php'; // Assurez-vous que le chemin d'accès vers votre fichier de connexion est correct

        // Échapper les données pour éviter les injections SQL
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $nom_personnel = mysqli_real_escape_string($conn, $_POST['nom_personnel']);
        $prenom_personnel = mysqli_real_escape_string($conn, $_POST['prenom_personnel']);
        $role_personnel = mysqli_real_escape_string($conn, $_POST['role_personnel']);
        $responsabilites = mysqli_real_escape_string($conn, $_POST['responsabilites']);
        $horaires = mysqli_real_escape_string($conn, $_POST['horaires']);
        $disponibilite = mysqli_real_escape_string($conn, $_POST['disponibilite']);
        $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $date_embauche = mysqli_real_escape_string($conn, $_POST['date_embauche']);

        // Pour les enseignants, récupérer l'ID de la classe
        $classe_id = 'NULL'; // Par défaut à NULL
        if ($role_personnel == 'enseignant' && isset($_POST['classe_id']) && !empty($_POST['classe_id'])) {
            $classe_id = mysqli_real_escape_string($conn, $_POST['classe_id']);
            
            // Vérifier si l'ID de la classe existe dans la table salles_classe
            $query_check_classe = "SELECT COUNT(*) AS count FROM salles_classe WHERE id = $classe_id";
            $result_check_classe = $conn->query($query_check_classe);
            $row_check_classe = $result_check_classe->fetch_assoc();
            if ($row_check_classe['count'] == 0) {
                // Si la classe n'existe pas, afficher une erreur et rediriger
                header("Location: ../../Dashboard_Admin/gestion_personnel.php?error=" . urlencode("La classe sélectionnée n'existe pas."));
                exit();
            }
        }

        // Requête pour mettre à jour les données du personnel dans la table staffs
        $sql = "UPDATE staffs SET 
                    nom = '$nom_personnel', 
                    prenom = '$prenom_personnel', 
                    role = '$role_personnel', 
                    telephone = '$telephone', 
                    email = '$email', 
                    date_embauche = '$date_embauche', 
                    classe_id = $classe_id 
                WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Modification réussie
            header("Location: ../../Dashboard_Admin/gestion_personnel.php?success=" . urlencode("Les données du personnel ont été modifiées avec succès"));
            exit();
        } else {
            // Erreur lors de la modification
            header("Location: ../../Dashboard_Admin/gestion_personnel.php?error=" . urlencode("Erreur lors de la modification des données du personnel : " . $conn->error));
            exit();
        }

        // Fermeture de la connexion à la base de données
        $conn->close();
    } else {
        // Données manquantes dans la requête POST
        header("Location: ../../Dashboard_Admin/gestion_personnel.php?error=" . urlencode("Toutes les données nécessaires n'ont pas été fournies"));
        exit();
    }
} else {
    // Requête non autorisée
    header("Location: ../../Dashboard_Admin/gestion_personnel.php?error=" . urlencode("Requête non autorisée"));
    exit();
}
?>
