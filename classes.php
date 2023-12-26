<?php
session_start();
include 'connes.php';
include 'helper.php';
include 'panel.php';

$error = null;
$success = null;
$an_scolair = null;
$lib_class = null;

$pdo = new PDO("mysql:host=localhost;dbname=iskoul", "root", "");

// Fonction pour ajouter une classe
function addClass($lib_class, $an_scolair, $num_prof)
{
    global $pdo, $error;

    try {
        // Vérifier si la classe existe déjà
        $stmt = $pdo->prepare("SELECT * FROM classes WHERE lib_class = :lib_class");
        $stmt->bindParam(':lib_class', $lib_class);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            addError($error, "La classe '$lib_class' existe déjà.");
        } else {
            // Insérer la nouvelle classe
            $stmt = $pdo->prepare("INSERT INTO classes (lib_class, an_scolair, num_prof) VALUES (:lib_class, :an_scolair, :num_prof)");
            $stmt->bindParam(':lib_class', $lib_class);
            $stmt->bindParam(':an_scolair', $an_scolair);
            $stmt->bindParam(':num_prof', $num_prof);

            if ($stmt->execute()) {
                adderror($error, "La classe '$lib_class' a été ajoutée avec succès en '$an_scolair'.");
            } else {
                addError($error, "Erreur lors de l'ajout de la classe.");
            }
        }
    } catch (PDOException $e) {
        addError($error, "Erreur PDO: " . $e->getMessage());
    }
}

// Ajouter une classe si le formulaire est soumis
if (isset($_POST['submit'])) {
    $lib_class = $_POST['lib_class'];
    $an_scolair = $_POST['an_scolair'];
    $num_prof = $_POST['num_prof']; // Ajout de cette ligne
    addClass($lib_class, $an_scolair, $num_prof);
}
// Récupérer le nombre d'élèves par classe
//$nombre_stud = getNumberOfStudentsPerClass();

$pdo = null;
?>

<html>

<style>
        .error-message {
            color: red;
            margin-top: -10px;
        }
      </style>
  
<!-- Formulaire pour ajouter une classe -->
<div id="right-panel" class="right-panel">

  <p class="error-message"><?php echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . $error . '</div>' ?><p>
<form method="post" action="" class="needs-validation" novalidate style="box-sizing: border-box;">
    <div class="form-row" style="margin-left: 5px; margin-right: 5px; margin-bottom: -30;">

        <div class="col-md-4 mb-3">
            <label for="validationTooltip01">classe</label>
            <input type="text" class="form-control" name="lib_class" placeholder="classe" value="<?php echo $lib_class; ?>">
        </div>

        <div class="col-md-4 mb-3">
            <label for="validationTooltip02">annee_scolaire</label>
            <input type="text" class="form-control" id="validationTooltip02" name="an_scolair" placeholder="anneee-scolaire" value="<?php echo $an_scolair; ?>">
        </div>

        <div class="col-md-4 mb-3">
            <label for="num_prof">Enseignant responsable :</label><br>
            <select name="num_prof" style="width: 330px; height: 35px;">
                <?php
                $pdo = new PDO("mysql:host=localhost;dbname=iskoul", "root", "");
                // Récupérer la liste des enseignants
                $stmt = $pdo->query("SELECT * FROM professors");
                $professors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Afficher les options du menu déroulant
                foreach ($professors as $professor) {
                    echo "<option value='" . $professor['num_prof'] . "'>" . $professor['prof_name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <button class="btn btn-primary" type="submit" name="submit">Submit form</button>
    </div>
</form>
</html>