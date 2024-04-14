<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}

// Inclusion du fichier de configuration et de la classe Tache
require_once("config.php");
require_once("Tache.php");

// Récupérer l'ID de l'utilisateur depuis la session
$id_utilisateur = $_SESSION['user_id'];

// Création d'une instance de la classe Tache en passant la connexion PDO
$tache = new Tache($conn);

// Récupérer les tâches de l'utilisateur avec les catégories correspondantes depuis la base de données
$taches = $tache->readTache();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Déclaration des métadonnées -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre de la page -->
    <title>Tableau de bord des tâches</title>
    <!-- Inclusion du CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body style="background-color: #ffffff;">
    <!-- Section principale de la page -->
    <section>
        <!-- Titre principal -->
        <h1 style="text-align: center; margin: 50px 0; color: #662B00;">Tableau de bord des tâches</h1>
        <!-- Conteneur principal -->
        <div class="container">
            <a href="createTache.php"><button class="btn btn-primary" style="background-color: #FFECA1; border-color: #FFECA1; color: #662B00;">Ajouter</button></a>
        </div>
    </section>
    <section style="margin: 50px 0;">
        <!-- Conteneur principal -->
        <div class="container">
            <!-- Tableau pour afficher les données -->
            <table class="table" style="background-color: #FFECA1;">
                <thead>
                    <!-- En-têtes de colonne -->
                    <tr style="color: #662B00;">
                        <th scope="col">Catégorie</th>
                        <th scope="col">Libellé</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Modifier</th>
                        <th scope="col">Détail</th>
                        <th scope="col">Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($taches as $tache) : ?>
                        <tr style="color: #662B00;">
                            <td><?php echo $tache['nom_categorie']; ?></td>
                            <td><?php echo $tache['libelle']; ?></td>
                            <td><?php echo $tache['statut']; ?></td>
                            <td><a href="update.php?id=<?php echo $tache['id_tache']; ?>" class="btn btn-primary" style="background-color: #FFECA1; border-color: #FFECA1; color: #662B00;">Modifier</a></td>
                            <td><a href="detailTache.php?id=<?php echo $tache['id_tache']; ?>" class="btn btn-primary" style="background-color: #FFECA1; border-color: #FFECA1; color: #662B00;">Détail</a></td>
                            <td><a href="delete.php?id=<?php echo $tache['id_tache']; ?>" class="btn btn-primary" style="background-color: #FFECA1; border-color: #FFECA1; color: #662B00;">Supprimer</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
