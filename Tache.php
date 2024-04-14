<?php
// Inclusion du fichier de configuration contenant les informations de connexion à la base de données
require_once("config.php");

// Définition de la classe Tache
class Tache {
    private $conn; // Connexion à la base de données

    // Constructeur de la classe, prend la connexion à la base de données en argument
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Méthode pour la connexion de l'utilisateur
    public function login($email, $mot_de_passe) {
        try {
            // Requête SQL pour vérifier les identifiants de l'utilisateur
            $sql = "SELECT * FROM utilisateurs WHERE email = :email AND mot_de_passe = :mot_de_passe";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':mot_de_passe', $mot_de_passe, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si l'utilisateur existe, démarrage de la session et redirection vers le tableau de bord
            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id_utilisateur'];
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Adresse e-mail ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la connexion : " . $e->getMessage();
        }
    }

    // Méthode pour vérifier si un utilisateur existe déjà dans la base de données
    public function userExists($email) {
        try {
            $sql = "SELECT COUNT(*) AS count FROM utilisateurs WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['count'] > 0;
        } catch (PDOException $e) {
            echo "Erreur lors de la vérification de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }

    // Méthode pour enregistrer un nouvel utilisateur
    public function register($nom, $prenom, $email, $telephone, $adresse, $poste, $mot_de_passe) {
        try {
            $sql = "INSERT INTO utilisateurs (nom, prenom, email, telephone, adresse, poste, mot_de_passe) 
                    VALUES (:nom, :prenom, :email, :telephone, :adresse, :poste, :mot_de_passe)";
            $stmt = $this->conn->prepare($sql);
            // Liaison des paramètres
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
            $stmt->bindParam(':poste', $poste, PDO::PARAM_STR);
            $stmt->bindParam(':mot_de_passe', $mot_de_passe, PDO::PARAM_STR);
            $stmt->execute();
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            echo "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }

    // Méthode pour créer une nouvelle tâche
    public function createTache($id_categorie, $id_utilisateur, $libelle, $description, $priorite, $statut) {
        try {
            // Requête SQL pour insérer une nouvelle tâche dans la base de données
            $sql = "INSERT INTO taches (id_categorie, id_utilisateur, libelle, description, priorite, statut) 
                    VALUES (:id_categorie, :id_utilisateur, :libelle, :description, :priorite, :statut)";
            $stmt = $this->conn->prepare($sql);
            // Liaison des paramètres
            $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
            $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':priorite', $priorite, PDO::PARAM_STR);
            $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
            $stmt->execute();
            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            echo "Erreur lors de la création de la tâche : " . $e->getMessage();
        }
    }

    // Méthode pour lire les tâches
    public function readTache(){
        try {
            // Requête SQL pour sélectionner les tâches avec leurs catégories correspondantes
            $sql = "SELECT t.*, c.nom_categorie 
                    FROM taches AS t
                    INNER JOIN taches_categories AS tc ON t.id_tache = tc.id_tache
                    INNER JOIN categories AS c ON tc.id_categorie = c.id_categorie";
            // Préparation de la requête
            $stmt = $this->conn->prepare($sql);
            // Exécution de la requête
            $stmt->execute();
            // Récupération des résultats
            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultats;
        } catch (PDOException $e) {
            // Gestion des erreurs
            die("Erreur: Impossible d'afficher les tâches - " . $e->getMessage());
        }
    }
    
    // Méthode pour mettre à jour une tâche
    public function updateTache($id_tache, $id_categorie, $description) {
        try {
            $sql = "UPDATE taches SET id_categorie = :id_categorie, description = :description WHERE id_tache = :id_tache";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':id_tache', $id_tache, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de la tâche : " . $e->getMessage();
            return false;
        }
    }

    // Méthode pour supprimer une tâche
    public function deleteTache($id_tache) {
        try {
            $sql = "DELETE FROM taches WHERE id_tache = :id_tache";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_tache', $id_tache, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de la tâche : " . $e->getMessage();
            return false;
        }
    }
}
?>
