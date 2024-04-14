<?php
session_start();

// Inclusion du fichier de configuration
require_once "config.php";
require_once "Tache.php";


// Vérification si le formulaire d'inscription est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $poste = $_POST['poste'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Création d'une instance de la classe Tache
    $tache = new Tache($conn); 

    // Vérification si l'utilisateur existe déjà dans la base de données
    if ($tache->userExists($email)) {
        echo "Désolé, vous avez déjà un compte. Veuillez vous connecter <a href='login.php'>ici</a>.";
    } else {
        // Appel de la méthode inscription
        $tache->register($nom, $prenom, $email, $telephone, $adresse, $poste, $mot_de_passe);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
        }

        .form-container {
            max-width: 500px;
            margin: auto;
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #662B00;
            border-radius: 10px 10px 0 0;
            color: #ffffff;
        }

        .card-body {
            padding: 30px;
        }

        .card-footer {
            background-color: #FFECA1;
            border-radius: 0 0 10px 10px;
            padding: 20px 30px;
        }

        .btn-primary {
            background-color: #662B00;
            border-color: #662B00;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #4b1b00;
            border-color: #4b1b00;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card form-container">
                    <div class="card-header">
                        <h2 class="text-center">Inscription</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="nom">Nom :</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                            <div class="form-group">
                                <label for="prenom">Prénom :</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email :</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="telephone">Téléphone :</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" required>
                            </div>
                            <div class="form-group">
                                <label for="adresse">Adresse :</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" required>
                            </div>
                            <div class="form-group">
                                <label for="poste">Poste :</label>
                                <input type="text" class="form-control" id="poste" name="poste" required>
                            </div>
                            <div class="form-group">
                                <label for="mot_de_passe">Mot de passe :</label>
                                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">S'inscrire</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <p class="text-center">Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
