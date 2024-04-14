<?php
// Vérifier si l'identifiant de la tâche est passé en paramètre dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Inclusion du fichier de configuration et de la classe Tache
    require_once("config.php");
    require_once("Tache.php");

    // Récupérer l'identifiant de la tâche depuis l'URL
    $id_tache = $_GET['id'];

    try {
        // Supprimer les enregistrements liés dans la table 'taches_categories'
        $sql_delete_taches_categories = "DELETE FROM taches_categories WHERE id_tache = :id_tache";
        $stmt_categories = $conn->prepare($sql_delete_taches_categories);
        $stmt_categories->bindParam(':id_tache', $id_tache, PDO::PARAM_INT);
        $stmt_categories->execute();


        // Redirection vers index.php après avoir supprimé la tâche
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    // Redirection vers index.php si l'identifiant de la tâche n'est pas spécifié
    header('Location: index.php');
    exit();
}
?>
