<?php
session_start();
include 'connes.php';
include 'helper.php';
include  'panel.php';

//initialisation des variables
$errors = [];
$sucess = null;
$student_name = null;
$student_surname = null;
$stud_brthd = null;
$stud_sexe = null;
$id_classe = null;
$student_phone = null;
$student_email = null;
$lib_class = null;


// Récupération des données du formulaire
if (isset($_POST['subnit'])) {
$student_name = $_POST['student_name'];
$student_surname = $_POST['student_surname'];
$stud_brthd = $_POST['stud_brthd'];
$stud_sexe = $_POST['stud_sexe'];
$student_phone = $_POST['student_phone'];
$student_email = $_POST['student_email'];
$lib_class = $_POST['lib_class'];


//connexion a la base de donnee
   $pdo = new PDO("mysql:host=localhost;dbname=iskoul", "root", "");


// Vérification si la classe existe déjà ou insertion si elle n'existe pas
$stmt = $pdo->prepare("INSERT IGNORE INTO classes (lib_class) VALUES (:lib_class)");
$stmt->bindParam(':lib_class', $lib_class);
$stmt->execute();

// Récupérer l'id de la classe en fonction du libellé
$sql = "SELECT id_class FROM classes WHERE lib_class = :lib_class";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':lib_class', $lib_class);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$id_class = $result['id_class'];
if ($result !== false) {
    $id_class = $result['id_class'];
} else {
   $errors = ["la classe n'existe pas"] ;

}



 if (!empty($student_name) && !empty($student_surname) && !empty($stud_brthd) && !empty($stud_sexe) && !empty($student_phone) && !empty($student_email) && !empty($id_class)) {
    

 // Vérifier l'unicité du numéro de téléphone
   $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE student_phone = :student_phone");
   $stmt->bindParam(':student_phone', $student_phone);
   $stmt->execute();
   $phoneExists = $stmt->fetchColumn();

    // Vérifier l'unicité de l'email
   $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE student_email = :student_email");
   $stmt->bindParam(':student_email', $student_email);
   $stmt->execute();
   $emailExists = $stmt->fetchColumn();

    // Vérifier si le numéro de téléphone et l'email sont uniques
    if ($phoneExists > 0) {
        $error .= "Erreur : Le numéro de téléphone existe déjà.";
    }

    if ($emailExists > 0) {
        $error .= "Erreur : L'email existe déjà.";
    }



// Requête d'insertion
$sql = "INSERT INTO students (student_name, student_surname, stud_brthd, stud_sexe, student_phone, student_email, id_class)
        VALUES (:student_name, :student_surname, :stud_brthd, :stud_sexe, :student_phone, :student_email, :id_class)";

// Préparation de la requête
$stmt = $pdo->prepare($sql);

// Liaison des paramètres
$stmt->bindParam(':student_name', $student_name);
$stmt->bindParam(':student_surname', $student_surname);
$stmt->bindParam(':stud_brthd', $stud_brthd);
$stmt->bindParam(':stud_sexe', $stud_sexe);
$stmt->bindParam(':student_phone', $student_phone);
$stmt->bindParam(':student_email', $student_email);
$stmt->bindParam(':id_class', $id_class);

// Exécution de la requête
if ($stmt->execute()) {
     $error= "L'eleve a été ajouté avec succès.";
} else {
    $error = "Erreur lors de l'ajout de l'eleve.";
}
 }else{ $error = ["veuillez remplir tout les champs"];
    
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
    <?php foreach ($errors as $error) : ?>
        <?php if (is_array($error)) : ?>
            <!-- Si c'est un tableau, affiche chaque élément du tableau -->
            <?php foreach ($error as $subError) : ?>
                <p class="error-message">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert"><?php echo $subError; ?></div>
                </p>
            <?php endforeach; ?>
        <?php else : ?>
            <!-- Sinon, affiche directement l'erreur -->
            <p class="error-message">
                <div class="alert alert-warning alert-dismissible fade show" role="alert"><?php echo $error; ?></div>
            </p>
        <?php endif; ?>
    <?php endforeach; ?>

    <form class="needs-validation" novalidate action="" method="POST" style="box-sizing: border-box;">
        <div class="form-row" style="margin-left: 5px; margin-right: 5px; margin-bottom: -30; "></div>
 <form class="needs-validation" novalidate action="" method = "POST" style = "box-sizing: border-box;">
  <div class="form-row" style="  margin-left: 5px; margin-right: 5px;     margin-bottom: -30; ">

    <div class="col-md-4 mb-3">
      <label for="validationTooltip01">First name</label>
      <input type="text" class="form-control" name="student_name" placeholder="First name" value="<?php echo $student_name; ?>">
    </div>

    <div class="col-md-4 mb-3">
      <label for="validationTooltip02">Last name</label>
      <input type="text" class="form-control" id="validationTooltip02" name="student_surname" placeholder="Last name" value="<?php echo $student_surname; ?>">
    </div>

    <div class="col-md-4 mb-3">
      <label for="validationTooltipUsername">email</label>
      <input type="email" class="form-control" name = "student_email" placeholder= "email" value="<?php echo $student_email; ?>" >
  </div>

    <div class="col-md-4 mb-3">
      <label for="validationTooltip04">phone</label>
      <input type="text" class="form-control" name="student_phone" placeholder="+**---" value="<?php echo $student_phone; ?>">
    </div>

     <div class="col-md-4 mb-3">
      <label for="validationTooltipUsername">birthday</label>
      <input type="date" class="form-control" name = "stud_brthd" >
  </div>

   <div class="col-md-4 mb-3">
      <label for="validationTooltipUsername">sexe</label>
      <input type="text" class="form-control" name = "stud_sexe" placeholder= "sexe" >
  </div>

    <div class="col-md-4 mb-3">
    <label for="validationTooltipUsername">sexe</label>
    <input type="text" class="form-control" name="lib_class" placeholder= "lib_class" ><br>
    </div>

  <button class="btn btn-primary" type="submit" name = "subnit">Submit form</button>
  </div>
</form>
</div>
</html>
