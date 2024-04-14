<?php
// Définition des informations de connexion à la base de données
$servername = "localhost"; // Adresse du serveur MySQL
$username = "root"; // Nom d'utilisateur MySQL
$password = ""; // Mot de passe MySQL
$dbname = "gestion_taches"; // Nom de la base de données

try {
    // Création d'une nouvelle instance de connexion PDO avec les informations fournies
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Configuration du mode d'erreur pour afficher les exceptions en cas de problème
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    // Capture des exceptions PDO et affichage du message d'erreur
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
