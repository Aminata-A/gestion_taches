<?php
// Inclusion du fichier de configuration contenant les informations de connexion à la base de données
require_once("config.php");

// Définition de l'interface tacheInterface qui spécifie les méthodes nécessaires pour interagir avec les tâches
interface tacheInterface {

    // Méthode pour la connexion d'un utilisateur
    public function login($email, $mot_de_pass);
    
    // Méthode pour l'enregistrement d'un nouvel utilisateur
    public function register($nom, $prenom, $email, $telephone, $adresse, $poste, $mot_de_passe);

    // Méthode pour la création d'une nouvelle tâche
    public function createTache($id_tache, $id_categorie);

    // Méthode pour la lecture d'une tâche spécifique
    public function readTache($id_tache);

    // Méthode pour la mise à jour d'une tâche existante
    public function updateTache($id_tache, $id_categorie);

    // Méthode pour la suppression d'une tâche
    public function deleteTache($id);
}
