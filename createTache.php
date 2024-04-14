<?php
// Démarrer la session
session_start();

// Inclusion du fichier de configuration et de la classe Tache
require_once("config.php");
require_once("Tache.php");

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php");
    exit();
}

// Récupérer l'ID de l'utilisateur depuis la session
$id_utilisateur = $_SESSION['user_id'];

// Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $id_categorie = $_POST['id_categorie'];
    $libelle = $_POST['libelle'];
    $description = $_POST['description'];
    $priorite = $_POST['priorite'];
    $statut = $_POST['statut'];
    $date_echeance = $_POST['date_echeance']; // Récupération de la date d'échéance

    try {
        // Requête pour insérer la tâche dans la table 'taches'
        $sql_insert_tache = "INSERT INTO taches (id_utilisateur, libelle, description, priorite, statut, date_echeance) 
                             VALUES (:id_utilisateur, :libelle, :description, :priorite, :statut, :date_echeance)";
        $req_insert_tache = $conn->prepare($sql_insert_tache);
        $req_insert_tache->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $req_insert_tache->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        $req_insert_tache->bindParam(':description', $description, PDO::PARAM_STR);
        $req_insert_tache->bindParam(':priorite', $priorite, PDO::PARAM_STR);
        $req_insert_tache->bindParam(':statut', $statut, PDO::PARAM_STR);
        $req_insert_tache->bindParam(':date_echeance', $date_echeance, PDO::PARAM_STR); // Liaison pour la date d'échéance
        $req_insert_tache->execute();
        
        // Récupérer l'ID de la tâche insérée
        $id_tache = $conn->lastInsertId();

        // Insérer l'association entre la tâche et la catégorie dans la table de liaison
        $sql_insert_tache_categorie = "INSERT INTO taches_categories (id_tache, id_categorie) VALUES (:id_tache, :id_categorie)";
        $req_insert_tache_categorie = $conn->prepare($sql_insert_tache_categorie);
        $req_insert_tache_categorie->bindParam(':id_tache', $id_tache, PDO::PARAM_INT);
        $req_insert_tache_categorie->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        $req_insert_tache_categorie->execute();

        // Redirection vers index.php après avoir créé la tâche
        header('Location: index.php');
        exit();
        
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une tâche</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 50px;
            background-color: #ffffff;
        }
        .container {
            max-width: 500px;
            background-color: #f7f7f7;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #662B00;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group label {
            color: #662B00;
        }
        .form-control {
            border-color: #662B00;
        }
        .btn-primary {
            background-color: #662B00;
            border-color: #662B00;
        }
        .btn-primary:hover {
            background-color: #4b1b00;
            border-color: #4b1b00;
        }
        .btn-cancel {
            background-color: #d9534f;
            border-color: #d9534f;
        }
        .btn-cancel:hover {
            background-color: #c9302c;
            border-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Créer une nouvelle tâche</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id_categorie">Catégorie :</label>
                <select id="id_categorie" name="id_categorie" class="form-control" required>
                    <?php
                    // Récupération des catégories depuis la base de données
                    $sql = "SELECT * FROM categories";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Affichage des options de la liste déroulante
                    foreach ($categories as $categorie) {
                        echo "<option value='{$categorie['id_categorie']}'>{$categorie['nom_categorie']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="libelle">Libellé :</label>
                <input type="text" id="libelle" name="libelle" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="priorite">Priorité :</label>
                <select id="priorite" name="priorite" class="form-control" required>
                    <option value="faible">Faible</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="elevee">Élevée</option>
                </select>
            </div>
            <div class="form-group">
                <label for="statut">Statut :</label>
                <select id="statut" name="statut" class="form-control" required>
                    <option value="a_faire">À faire</option>
                    <option value="en_cours">En cours</option>
                    <option value="terminee">Terminée</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date_echeance">Date d'échéance :</label>
                <input type="date" id="date_echeance" name="date_echeance" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mr-2">Créer la tâche</button>
            <a href="index.php" class="btn btn-cancel">Annuler</a>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
