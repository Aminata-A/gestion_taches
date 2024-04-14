<?php
// Inclusion du fichier de configuration et de la classe Tache
require_once("config.php");
require_once("Tache.php");

// Vérification si l'ID de la tâche est passé en paramètre dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_tache = $_GET['id'];

    // Récupération des informations de la tâche à mettre à jour depuis la base de données
    $sql = "SELECT * FROM taches WHERE id_tache = :id_tache";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_tache', $id_tache, PDO::PARAM_INT);
    $stmt->execute();
    $tache = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le formulaire est soumis pour mettre à jour la tâche
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des données du formulaire
        $libelle = $_POST['libelle'];
        $description = $_POST['description'];
        $priorite = $_POST['priorite'];
        $statut = $_POST['statut'];

        try {
            // Requête pour mettre à jour la tâche dans la table 'taches'
            $sql_update_tache = "UPDATE taches 
                                 SET libelle = :libelle, description = :description, priorite = :priorite, statut = :statut
                                 WHERE id_tache = :id_tache";
            $req_update_tache = $conn->prepare($sql_update_tache);
            $req_update_tache->bindParam(':libelle', $libelle, PDO::PARAM_STR);
            $req_update_tache->bindParam(':description', $description, PDO::PARAM_STR);
            $req_update_tache->bindParam(':priorite', $priorite, PDO::PARAM_STR);
            $req_update_tache->bindParam(':statut', $statut, PDO::PARAM_STR);
            $req_update_tache->bindParam(':id_tache', $id_tache, PDO::PARAM_INT);
            $req_update_tache->execute();

            header('Location:index.php');
            exit(); // Ajout pour arrêter l'exécution après la redirection
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
} else {
    // Rediriger vers une page d'erreur si l'ID de la tâche n'est pas passé en paramètre
    header("Location: erreur.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une tâche</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 50px;
            background-color: #ffffff;
            font-family: Arial, sans-serif;
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
        <h2>Modifier la tâche</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="libelle">Libellé :</label>
                <input type="text" id="libelle" name="libelle" class="form-control" value="<?php echo $tache['libelle']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" class="form-control" required><?php echo $tache['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="priorite">Priorité :</label>
                <select id="priorite" name="priorite" class="form-control" required>
                    <option value="Faible" <?php if ($tache['priorite'] == 'Faible') echo 'selected'; ?>>Faible</option>
                    <option value="Moyenne" <?php if ($tache['priorite'] == 'Moyenne') echo 'selected'; ?>>Moyenne</option>
                    <option value="Élevée" <?php if ($tache['priorite'] == 'Élevée') echo 'selected'; ?>>Élevée</option>
                </select>
            </div>
            <div class="form-group">
                <label for="statut">Statut :</label>
                <select id="statut" name="statut" class="form-control" required>
                    <option value="À faire" <?php if ($tache['statut'] == 'À faire') echo 'selected'; ?>>À faire</option>
                    <option value="En cours" <?php if ($tache['statut'] == 'En cours') echo 'selected'; ?>>En cours</option>
                    <option value="Terminée" <?php if ($tache['statut'] == 'Terminée') echo 'selected'; ?>>Terminée</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mr-2">Modifier la tâche</button>
            <a href="index.php?id=<?php echo $tache['id_tache']; ?>" class="btn btn-cancel">Annuler</a>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>