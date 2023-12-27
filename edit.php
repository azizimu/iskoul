<?php
session_start();
include 'connes.php';
include 'helper.php';
require './config/helpers.php';
include 'panel.php';

// Initialisation des variables
$error = null;
$success = null;
$professor ;
$db = initDbConnexion();


// Vérifier si l'ID du professeur à mettre à jour est passé en paramètre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $prof_id = $_GET['id'];

    // Récupérer les informations actuelles du professeur depuis la base de données
    $stmt = $db->prepare("SELECT * FROM professors WHERE num_prof = :id");
    $stmt->bindParam(':id', $prof_id);
    $stmt->execute();
    $professor = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le professeur existe
    if (!$professor) {
        $error = "Le professeur que vous essayez de mettre à jour n'existe pas.";
    }
}

// Traitement de la mise à jour lorsque le formulaire est soumis
if (isset($_POST['update'])) {
    // Récupérez les nouvelles valeurs depuis le formulaire

    $new_prof_name = $_POST['prof_name'];
    $new_prof_surname = $_POST['prof_surname'];
    $new_prof_email = $_POST['prof_email'];
    $new_prof_matiere = $_POST['prof_matiere'];
    $new_prof_phone = $_POST['prof_phone'];
    $new_prof_grade = $_POST['prof_grade'];
    $new_prof_brthd = $_POST['prof_brthd'];
    $new_prof_sexe = $_POST['prof_sexe'];

    // Mettre à jour les informations dans la base de données

    $update_stmt = $db->prepare("UPDATE professors SET prof_name = :new_prof_name, prof_surname = :new_prof_surname, prof_email = :new_prof_email, prof_matiere = :new_prof_matiere, prof_phone = :new_prof_phone, prof_grade = :new_prof_grade, prof_brthd = :new_prof_brthd, prof_sexe = :new_prof_sexe WHERE num_prof = :num_prof");
    $update_stmt->bindParam(':new_prof_name', $new_prof_name);
    $update_stmt->bindParam(':new_prof_surname', $new_prof_surname);
    $update_stmt->bindParam(':new_prof_email', $new_prof_email);
    $update_stmt->bindParam(':new_prof_matiere', $new_prof_matiere);
    $update_stmt->bindParam(':new_prof_phone', $new_prof_phone);
    $update_stmt->bindParam(':new_prof_grade', $new_prof_grade);
    $update_stmt->bindParam(':new_prof_brthd', $new_prof_brthd);
    $update_stmt->bindParam(':new_prof_sexe', $new_prof_sexe);
    $update_stmt->bindParam(':num_prof', $prof_id);

    if ($update_stmt->execute()) {
        $error = "Les informations du professeur ont été mises à jour avec succès.";
        // Rafraîchir les données actuelles du professeur

        $professor = [
            'prof_name' => $new_prof_name,
            'prof_surname' => $new_prof_surname,
            'prof_email' => $new_prof_email,
            'prof_matiere' => $new_prof_matiere,
            'prof_phone' => $new_prof_phone,
            'prof_grade' => $new_prof_grade,
            'prof_brthd' => $new_prof_brthd,
            'prof_sexe' => $new_prof_sexe
        ];
    } else {
        $error = "Erreur lors de la mise à jour des informations du professeur.";

    }//redirectTolist();
}

// Fermeture de la connexion
$pdo = null;
?>

<html>

<style>
    .error-message {
        color: red;
        margin-top: -10px;
    }

    .success-message {
        color: green;
        margin-top: -10px;
    }
</style>

<div id="right-panel" class="right-panel">

  <p class="error-message"><?php echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . $error . '</div>' ?></p>

 <form class="needs-validation" novalidate action="" method = "POST" style = "box-sizing: border-box;">
  <div class="form-row" style="  margin-left: 5px; margin-right: 5px;     margin-bottom: -30; ">



    <div class="col-md-4 mb-3">
        <label for="validationTooltip01">First name</label>
        <input type="text" class="form-control" name="prof_name" placeholder="First name" value="<?php echo $professor['prof_name'] ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label for="validationTooltip02">Last name</label>
        <input type="text" class="form-control" name="prof_surname" placeholder="Last name" value="<?php echo  $professor['prof_surname'] ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label for="validationTooltipUsername">Email</label>
        <input type="email" class="form-control" name="prof_email" placeholder="Email" value="<?php echo $professor['prof_email']; ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label for="validationTooltip03">Matiere</label>
        <input type="text" class="form-control" name="prof_matiere" placeholder="Matiere" value="<?php echo $professor['prof_matiere']; ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label for="validationTooltip04">Phone</label>
        <input type="text" class="form-control" name="prof_phone" placeholder="Phone" value="<?php echo $professor['prof_phone']; ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label for="validationTooltip05">Grade</label>
        <input type="text" class="form-control" name="prof_grade" placeholder="Grade" value="<?php echo $professor['prof_grade']; ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label for="validationTooltip06">Birthday</label>
        <input type="date" class="form-control" name="prof_brthd" value="<?php echo $professor['prof_brthd']; ?>">
    </div>

 <div class="col-md-4 mb-3">
    <label for="prof_sexe">sexe :</label><br>
    <select name="prof_sexe" style="width: 330px; height: 35px;" placeholder="sexe"> 
        <option value="Masculin" <?php echo ($professor['prof_sexe'] == 'Masculin') ? 'selected' : ''; ?>>Masculin</option>
        <option value="Féminin" <?php echo ($professor['prof_sexe'] == 'Féminin') ? 'selected' : ''; ?>>Féminin</option>
        <option value="Bisexuel" <?php echo ($professor['prof_sexe'] == 'Bisexuel') ? 'selected' : ''; ?>>Bisexuel</option>
    </select>
</div>

    <!-- Ajoutez d'autres champs de formulaire avec les valeurs actuelles -->

   <button class="btn btn-primary" type="submit" name="update">Update information</button>
    <a href="listeprof.php" class="btn btn-primary" style=" margin-left : 20px; margin-right: -10px; margin-bottom: -30 ;    text-align: center;"> list profs</a>
</div>
 </form>
</div>
</html>