<?php
session_start();
include 'connes.php';
include 'helper.php';
include  'panel.php';

//initialisation des variables
$error = null;
$sucess = null;
$prof_name = null;
$prof_surname = null;
$prof_brthd = null;
$prof_sexe = null;
$prof_matiere = null;
$prof_grade = null;
$prof_phone = null;
$prof_email = null;
$phoneCheck = null;

// Récupération des données du formulaire
if (isset($_POST['subnit'])) {
$prof_name = $_POST['prof_name'];
$prof_surname = $_POST['prof_surname'];
$prof_brthd = $_POST['prof_brthd'];
$prof_sexe = $_POST['prof_sexe'];
$prof_matiere = $_POST['prof_matiere'];
$prof_grade = $_POST['prof_grade'];
$prof_phone = $_POST['prof_phone'];
$prof_email = $_POST['prof_email'];

 if (!empty($prof_name) && !empty($prof_surname) && !empty($prof_brthd) && !empty($prof_sexe) && !empty($prof_matiere) && !empty($prof_grade) && !empty($prof_phone) && !empty($prof_email)) {
    //connexion a la base de donnee
   $database = new PDO("mysql:host=localhost;dbname=iskoul", "root", "");

 // Vérifier l'unicité du numéro de téléphone
   $stmt = $database->prepare("SELECT COUNT(*) FROM professors WHERE prof_phone = :prof_phone");
   $stmt->bindParam(':prof_phone', $prof_phone);
   $stmt->execute();
   $phoneExists = $stmt->fetchColumn();
   
       if ($phoneExists > 0) {
       addError($error," Le numéro de téléphone existe déjà.");
    }

    // Vérifier l'unicité de l'email
   $stmt = $database->prepare("SELECT COUNT(*) FROM professors WHERE prof_email = :prof_email");
   $stmt->bindParam(':prof_email', $prof_email);
   $stmt->execute();
   $emailExists = $stmt->fetchColumn();

    // Vérifier si le numéro de téléphone et l'email sont uniques

    if ($emailExists > 0) {
       addError($error, " L'email existe déjà.");
    }


try{

// Requête d'insertion
$sql = "INSERT INTO professors (prof_name, prof_surname, prof_brthd, prof_sexe, prof_matiere, prof_grade, prof_phone, prof_email)
        VALUES (:prof_name, :prof_surname, :prof_brthd, :prof_sexe, :prof_matiere, :prof_grade, :prof_phone, :prof_email)";

// Préparation de la requête
$stmt = $database->prepare($sql);

// Liaison des paramètres
$stmt->bindParam(':prof_name', $prof_name);
$stmt->bindParam(':prof_surname', $prof_surname);
$stmt->bindParam(':prof_brthd', $prof_brthd);
$stmt->bindParam(':prof_sexe', $prof_sexe);
$stmt->bindParam(':prof_matiere', $prof_matiere);
$stmt->bindParam(':prof_grade', $prof_grade);
$stmt->bindParam(':prof_phone', $prof_phone);
$stmt->bindParam(':prof_email', $prof_email);

// Exécution de la requête
if ($stmt->execute()) {
     addError($error, "Le professeur a été ajouté avec succès.");
} else {
                addError($error," lors de l'ajout du professeur.");
            }
        } catch (PDOException $e) {
            // Gérer l'exception ici, par exemple, en ne faisant rien ou en enregistrant les erreurs dans un fichier de journal
        }
    } else {
        addError($error,"Veuillez remplir tous les champs.");
    }

// Fermeture de la connexion
$pdo = null;
}
?>

<html>

<style>
        .error-message {
            color: red;
            margin-top: -10px;
        }
      </style>
  
<div id="right-panel" class="right-panel">

  <p class="error-message"><?php echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . $error . '</div>' ?></p>

 <form class="needs-validation" novalidate action="" method = "POST" style = "box-sizing: border-box;">
  <div class="form-row" style="  margin-left: 5px; margin-right: 5px;     margin-bottom: -30; ">

    <div class="col-md-4 mb-3">
      <label for="validationTooltip01">First name</label>
      <input type="text" class="form-control" name="prof_name" placeholder="First name" value="<?php echo $prof_name; ?>">
    </div>

    <div class="col-md-4 mb-3">
      <label for="validationTooltip02">Last name</label>
      <input type="text" class="form-control" id="validationTooltip02" name="prof_surname" placeholder="Last name" value="<?php echo $prof_surname; ?>">
    </div>

    <div class="col-md-4 mb-3">
      <label for="validationTooltipUsername">email</label>
      <input type="email" class="form-control" name = "prof_email" placeholder= "email" value="<?php echo $prof_email; ?>" >
  </div>

  
    <div class="col-md-4 mb-3">
      <label for="validationTooltip03">matiere</label>
      <input type="text" class="form-control" name="prof_matiere" placeholder="matiere" value="<?php echo $prof_matiere; ?>">
    </div>

    <div class="col-md-4 mb-3">
      <label for="validationTooltip04">phone</label>
      <input type="text" class="form-control" name="prof_phone" placeholder="+**---" value="<?php echo $prof_phone; ?>">
    </div>

    <div class="col-md-4 mb-3">
      <label for="validationTooltip05">grade</label>
      <input type="text" class="form-control" name="prof_grade" placeholder="grade" value="<?php echo $prof_grade; ?>">
    </div>

     <div class="col-md-4 mb-3">
      <label for="validationTooltipUsername">birthday</label>
      <input type="date" class="form-control" name = "prof_brthd" >
  </div>

   <div class="col-md-4 mb-3">
      <label for="validationTooltipUsername">sexe</label>
      <input type="text" class="form-control" name = "prof_sexe" placeholder= "sexe" >
  </div>

  <button class="btn btn-primary" type="subnit" name = "subnit">Submit form</button>
  </div>
</form>
</div>
</html>














?>