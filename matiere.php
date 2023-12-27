<?php
session_start();
include 'connes.php';
include 'helper.php';
include 'panel.php';

$error = null;
$success = null;
$mat_name = null;
$filename = null; // Ajoutez cette ligne

$pdo = new PDO("mysql:host=localhost;dbname=iskoul", "root", "");

// Fonction pour ajouter une matière
function addmatiere($mat_name, $filename, $pathf)
{
    global $pdo, $error;

    try {
        // Vérifier si la matière existe déjà
        $stmt = $pdo->prepare("SELECT * FROM matiere WHERE mat_name = :mat_name");
        $stmt->bindParam(':mat_name', $mat_name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            addError($error, "La matiere '$mat_name' existe déjà.");
        } else {
            // Insérer la nouvelle matière avec le nom du fichier
            $stmt = $pdo->prepare("INSERT INTO matiere (mat_name, filename, pathf) VALUES (:mat_name, :filename, :pathf)");
            $stmt->bindParam(':mat_name', $mat_name);
            $stmt->bindParam(':filename', $filename); // Ajoutez cette ligne
            $stmt->bindParam(':pathf', $pathf);

            if ($stmt->execute()) {
                adderror($error, "La matiere '$mat_name' a été ajoutée avec succès.");
            } else {
                addError($error, "Erreur lors de l'ajout de la matiere.");
            }
        }
    } catch (PDOException $e) {
        addError($error, "Erreur PDO: " . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $targetDir = "uploads/";
    $filename = basename($_FILES["file"]["name"]);
    $targetFile = $targetDir . $filename;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        // Ajout de cette ligne
        addmatiere($mat_name, $filename, $targetFile);

        echo "Le fichier $filename a été téléchargé avec succès.";
    } else {
        echo "Une erreur s'est produite lors du téléchargement du fichier.";
    }
}

// Ajouter une matière si le formulaire est soumis
if (isset($_POST['submit'])) {
    $mat_name = $_POST['lib_matiere'];
    // Utilisez le nom du fichier sélectionné automatiquement
    addmatiere($mat_name, $filename, "");
}

$pdo = null;
?>

<html>

<style>
    .error-message {
        color: red;
        margin-top: -10px;
    }
</style>

<!-- Formulaire pour ajouter une matière -->
<div id="right-panel" class="right-panel">

    <p class="error-message"><?php echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . $error . '</div>' ?><p>
    <form method="post" action="" class="needs-validation" novalidate style="box-sizing: border-box;">
        <div class="form-row" style="margin-left: 5px; margin-right: 5px; margin-bottom: -30;">

            <div class="col-md-4 mb-3">
                <label for="validationTooltip01">matiere</label>
                <input type="text" class="form-control" name="lib_matiere" placeholder="matiere" value="<?php echo $mat_name; ?>">
            </div>

            <label for="file">Sélectionner un fichier :</label>
            <input type="file" name="file" id="file">
            <br>

            <button class="btn btn-primary" type="submit" name="submit">Submit form</button>
        </div>
    </form>
</html>
