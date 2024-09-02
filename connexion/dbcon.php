<?php

$servername = "localhost";
$username = "phpmyadmin";
$password = "1234";
$database = "gestion_ecole";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $database);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

?>
