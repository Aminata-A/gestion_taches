<?php
session_start();
require_once("config.php");
require_once "Tache.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "HERE 2";
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    // Vérification des identifiants dans la base de données
    $sql = "SELECT * FROM utilisateurs WHERE email = :email AND mot_de_passe = :mot_de_passe";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mot_de_passe', $mot_de_passe); 
    $stmt->execute();


    // Récupérer l'utilisateur
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $user;
    if ($user) {
        // Utilisateur trouvé, connectez-le
        $_SESSION["user_id"] = $user['id_utilisateur'];
        $_SESSION["email"] = $user['email'];
        $_SESSION["mot_de_passe"] = $user['mot_de_passe'];

        // Redirection vers la page d'accueil
        header("Location: index.php");
        exit();
    } else {
        // Utilisateur non trouvé, redirigez vers la page de connexion avec un message d'erreur
        header("Location: login.php?error=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
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

        .btn-primary {
            background-color: #662B00;
            border-color: #662B00;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #4b1b00;
            border-color: #4b1b00;
        }

        .alert-danger {
            background-color: #FFECA1;
            border-color: #FFECA1;
            color: #662B00;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Connexion</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="email">Email :</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="mot_de_passe">Mot de passe :</label>
                                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Se connecter</button>
                            </div>
                        </form>
                        <?php
                        // Afficher un message d'erreur si les identifiants sont incorrects
                        if (isset($_GET['error']) && $_GET['error'] == 1) {
                            echo '<div class="alert alert-danger mt-3" role="alert">Identifiants incorrects. Veuillez réessayer.</div>';
                        }
                        ?>
                        <div class="text-center mt-3">
                        <p>Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
                        </div>
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
