<?php
// Inclusion du fichier de configuration et de la classe Tache
require_once("config.php");
require_once("Tache.php");

// Vérification si un identifiant de tâche est passé en paramètre dans l'URL
if (isset($_GET['id'])) {
    $id_tache = $_GET['id'];

    try {
        // Requête pour récupérer les détails de la tâche
        $sql = "SELECT t.*, c.nom_categorie, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur, u.poste
                FROM taches AS t 
                INNER JOIN utilisateurs AS u ON t.id_utilisateur = u.id_utilisateur
                INNER JOIN taches_categories AS tc ON t.id_tache = tc.id_tache
                INNER JOIN categories AS c ON tc.id_categorie = c.id_categorie
                WHERE t.id_tache = :id_tache";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_tache', $id_tache, PDO::PARAM_INT);
        $stmt->execute();
        $tache = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tache) {
            echo "La tâche spécifiée n'existe pas.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Aucun identifiant de tâche spécifié.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la tâche</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 50px;
            background-color:#662B00; /* Couleur de fond */
            color: #ffffff; /* Couleur du texte */
        }
        .container {
            max-width: 600px;
            background-color: #ffffff; /* Couleur de fond du conteneur */
            border-radius: 10px; /* Coins arrondis */
            padding: 20px; /* Espace intérieur */
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2); /* Ombre légère */
            margin-top: 20px; /* Marge supérieure */
        }
        h2 {
            color: #662B00; /* Couleur du titre */
        }
        label {
            color: #662B00; /* Couleur du label */
            font-weight: bold; /* Texte en gras */
        }
        input[type="text"], select {
            width: 100%; /* Largeur à 100% */
            padding: 10px; /* Espace intérieur */
            margin-bottom: 20px; /* Marge inférieure */
            border: 2px solid #662B00; /* Bordure */
            border-radius: 5px; /* Coins arrondis */
            box-sizing: border-box; /* La largeur inclut la bordure et le rembourrage */
        }
        textarea {
            width: 100%; /* Largeur à 100% */
            padding: 10px; /* Espace intérieur */
            margin-bottom: 20px; /* Marge inférieure */
            border: 2px solid #662B00; /* Bordure */
            border-radius: 5px; /* Coins arrondis */
            box-sizing: border-box; /* La largeur inclut la bordure et le rembourrage */
            resize: none; /* Désactiver le redimensionnement */
        }
        .btn-primary {
            background-color: #FFECA1; /* Couleur du bouton principal */
            border-color: #FFECA1; /* Couleur de la bordure */
            color: #662B00; /* Couleur du texte */
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease; /* Transition fluide */
        }
        .btn-primary:hover {
            background-color: #FFD200; /* Couleur du bouton principal au survol */
            border-color: #FFD200; /* Couleur de la bordure au survol */
            color: #662B00; /* Couleur du texte au survol */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Détails de la tâche</h2>
        <form>
            <div class="form-group">
                <label for="categorie">Catégorie :</label>
                <input type="text" id="categorie" name="categorie" class="form-control" value="<?php echo isset($tache['nom_categorie']) ? $tache['nom_categorie'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="libelle">Libellé :</label>
                <input type="text" id="libelle" name="libelle" class="form-control" value="<?php echo isset($tache['libelle']) ? $tache['libelle'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" class="form-control" rows="5" readonly><?php echo isset($tache['description']) ? $tache['description'] : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="priorite">Priorité :</label>
                <input type="text" id="priorite" name="priorite" class="form-control" value="<?php echo isset($tache['priorite']) ? $tache['priorite'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="statut">Statut :</label>
                <input type="text" id="statut" name="statut" class="form-control" value="<?php echo isset($tache['statut']) ? $tache['statut'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="date_ajout">Date d'ajout :</label>
                <input type="text" id="date_ajout" name="date_ajout" class="form-control" value="<?php echo isset($tache['date_ajout']) ? $tache['date_ajout'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="date_echeance">Date d'échéance :</label>
                <input type="text" id="date_echeance" name="date_echeance" class="form-control" value="<?php echo isset($tache['date_echeance']) ? $tache['date_echeance'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="ajoute_par">Ajoutée par :</label>
                <input type="text" id="ajoute_par" name="ajoute_par" class="form-control" value="<?php echo isset($tache['nom_utilisateur']) && isset($tache['prenom_utilisateur']) && isset($tache['poste']) ? $tache['nom_utilisateur'] . ' ' . $tache['prenom_utilisateur'] . ', ' . $tache['poste'] : ''; ?>" readonly>
            </div>
            <a href="index.php" class="btn btn-primary">Retour</a>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

