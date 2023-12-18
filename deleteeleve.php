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
    $studentsId = $_GET['id'];

    // Vérifier si le formulaire de confirmation a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // L'utilisateur a confirmé la suppression
        $query = "DELETE FROM students WHERE matricule = :id";
        $statement = $bd->prepare($query);
        $statement->bindParam(":id", $studentsId, PDO::PARAM_INT);

        if ($statement->execute()) {
            $error = "Eleve supprimé avec succès.";
            // Rediriger vers la page principale ou une autre page après la suppression
            redirectTolistel();
            exit();
        } else {
            $error= "Erreur lors de la suppression de l'eleve.";
        }
    }
} else {
    $error= "ID de l'eleve non spécifié.";
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

        .cancel-btnn {
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
            <p>Voulez-vous vraiment supprimer ce eleve?</p>
            <form method="post">
                  <p class="error-message"><?php  '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . $error . '</div>' ?></p>
                <input type="submit" name="confirm" class="confirm-btn" value="Confirmer">
                <a href="listeleve.php" class="cancel-btnn">Annuler</a>
            </form>
        </div>
    </div>
</body>
</html>
