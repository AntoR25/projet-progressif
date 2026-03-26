<?php
// Connexion au serveur et à la base de données
$servername = "localhost";
$dbname = "examen";
$username = "root";
$password = "";

try {
    $connexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Paramètres de connexion PDO pour afficher les erreurs et activer le mode exception
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    die();
}
$connexion->exec("SET CHARACTER SET utf8");
?>







