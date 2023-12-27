<?php
session_start();
include 'connes.php';
include 'helper.php';
include 'panel.php';

$error = null;
$database = new connec();
$bd = $database->getConnection();
$bd_name = 'iskoul';
$bd->exec("USE $bd_name");

// Vérifier si l'id est présent dans l'URL
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $professorId = $_GET['id'];

    // L'utilisateur a confirmé la suppression
    $query = "DELETE FROM professors WHERE num_prof = :id";
    $statement = $bd->prepare($query);
    $statement->bindParam(":id", $professorId, PDO::PARAM_INT);
    if ($statement->execute()) {
        $error = "Professeur supprimé avec succès.";
        // Rediriger vers la page principale ou une autre page après la suppression
      //  redirectTolist();
    } else {
        $error= "Erreur lors de la suppression du professeur.";
    }
} else {
    $error= "ID du professeur non spécifié.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Balises head ... -->
</head>
<body>
    <div id="right-panel" class="right-panel">
        <div class="card-body--"  style="padding-left: 8px">
            <h2>Suppression de professeur</h2>
            <p><?php echo $error; ?></p>
            <a href="listeprof.php" class="btn btn-blue mb-3" style= background-color:#88F  >Retour à la liste</a>
        </div>
    </div>
</body>
</html>
