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

    // Vérifier si le formulaire de confirmation a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // L'utilisateur a confirmé la suppression
        $query = "DELETE FROM professors WHERE num_prof = :id";
        $statement = $bd->prepare($query);
        $statement->bindParam(":id", $professorId, PDO::PARAM_INT);

        if ($statement->execute()) {
            $error = "Professeur supprimé avec succès.";
            // Rediriger vers la page principale ou une autre page après la suppression
            redirectTolist();
            exit();
        } else {
            $error= "Erreur lors de la suppression du professeur.";
        }
    }
} else {
    $error= "ID du professeur non spécifié.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Balises head ... -->
    <style>
        .confirm-btn {
            background-color: #007bff;
            color: #fff;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
        }

        .cancel-btn {
            background-color: #dc3545;
            color: #fff;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="right-panel" class="right-panel">
        <div class="card-body--"  style = "padding-left: 8px">
            
            <h2>Confirmation de suppression</h2>
            <p>Voulez-vous vraiment supprimer ce professeur?</p>
            <form method="post">
                  <p class="error-message"><?php  '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . $error . '</div>' ?></p>
                <input type="submit" name="confirm" class="confirm-btn" value="Confirmer">
                <a href="listeprof.php" class="cancel-btn">Annuler</a>
            </form>
        </div>
    </div>
</body>
</html>
