<?php
session_start();
include 'connes.php';
include 'helper.php';
include 'panel.php';

$error = null;

$pdo = new PDO("mysql:host=localhost;dbname=iskoul", "root", "");

// Vérifier si un ID est passé via la requête
if (isset($_GET['id'])) {
    $matricule = $_GET['id'];

    // Sélectionner l'élève avec l'ID spécifié
    $stmt = $pdo->prepare("SELECT students.*, classes.lib_class 
                            FROM students 
                            LEFT JOIN classes ON students.id_class = classes.id_class
                            WHERE matricule = :matricule");
    $stmt->bindParam(':matricule', $matricule);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'élève existe
    if (!$student) {
        addError($error, "Élève non trouvé.");
       echo "<script> window.Location.href=' listeleve.php';</script>"; // Rediriger vers la liste des élèves
        exit();
    }
} else {
    addError($error, "ID de l'élève non spécifié.");
   // header("Location: eleves.php"); // Rediriger vers la liste des élèves
    exit();
}

// Si le formulaire de mise à jour est soumis
if (isset($_POST['update'])) {
    // Récupérer les nouvelles données du formulaire
    $new_name = $_POST['new_name'];
    $new_surname = $_POST['new_surname'];
    $new_brthd = $_POST['new_brthd'];
    $new_sexe = $_POST['new_sexe'];
    $new_phone = $_POST['new_phone'];
    $new_email = $_POST['new_email'];
    $new_lib_class = $_POST['new_lib_class'];

    // Vérifier si la classe existe déjà ou l'insérer si elle n'existe pas
    $stmt = $pdo->prepare("INSERT IGNORE INTO classes (lib_class) VALUES (:lib_class)");
    $stmt->bindParam(':lib_class', $new_lib_class);
    $stmt->execute();

    // Récupérer l'ID de la classe en fonction du libellé
    $stmt = $pdo->prepare("SELECT id_class FROM classes WHERE lib_class = :lib_class");
    $stmt->bindParam(':lib_class', $new_lib_class);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result === false) {
        addError($error, "La classe '$new_lib_class' n'existe pas dans la base de données.");
    } else {
        $new_id_class = $result['id_class'];
    }

    // Préparer et exécuter la requête de mise à jour
    $update_stmt = $pdo->prepare("UPDATE students SET 
        student_name = :new_name,
        student_surname = :new_surname,
        stud_brthd = :new_brthd,
        stud_sexe = :new_sexe,
        student_phone = :new_phone,
        student_email = :new_email,
        id_class = :new_id_class
        WHERE matricule = :matricule");

    $update_stmt->bindParam(':new_name', $new_name);
    $update_stmt->bindParam(':new_surname', $new_surname);
    $update_stmt->bindParam(':new_brthd', $new_brthd);
    $update_stmt->bindParam(':new_sexe', $new_sexe);
    $update_stmt->bindParam(':new_phone', $new_phone);
    $update_stmt->bindParam(':new_email', $new_email);
    $update_stmt->bindParam(':new_id_class', $new_id_class);
    $update_stmt->bindParam(':matricule', $matricule);

    if ($update_stmt->execute()) {
        addError($error, "Les informations de l'élève ont été mises à jour avec succès.");
      // redirectTolistel();
    } else {
        addError($error, "Erreur lors de la mise à jour des informations de l'élève.");
    }
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

<div id="right-panel" class="right-panel">

  <p class="error-message"><?php echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . $error . '</div>' ?></p>

 <form class="needs-validation"  action="" method = "POST" style = "box-sizing: border-box;">
  <div class="form-row" style="  margin-left: 5px; margin-right: 5px;     margin-bottom: -30; ">



    <div class="col-md-4 mb-3">
        <label for="validationTooltip01">First name</label>
        <input type="text" class="form-control" name="new_name" placeholder="First name" value="<?php echo $student['student_name'] ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label for="validationTooltip02">Last name</label>
        <input type="text" class="form-control" name="new_surname" placeholder="Last name" value="<?php echo  $student['student_surname'] ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label for="validationTooltipUsername">Email</label>
        <input type="email" class="form-control" name="new_email" placeholder="Email" value="<?php echo $student['student_email']; ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label for="validationTooltip04">Phone</label>
        <input type="text" class="form-control" name="new_phone" placeholder="Phone" value="<?php echo $student['student_phone']; ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label for="validationTooltip06">Birthday</label>
        <input type="date" class="form-control" name="new_brthd" value="<?php echo $student['stud_brthd']; ?>">
    </div>
<div class="col-md-4 mb-3">
    <label for="stud_sexe">Sexe :</label><br>
    <select name="new_sexe" style="width: 330px; height: 35px;">
        <option value="Masculin" <?php echo ($student['stud_sexe'] == 'Masculin') ? 'selected' : ''; ?>>Masculin</option>
        <option value="Féminin" <?php echo ($student['stud_sexe'] == 'Féminin') ? 'selected' : ''; ?>>Féminin</option>
        <option value="Bisexuel" <?php echo ($student['stud_sexe'] == 'Bisexuel') ? 'selected' : ''; ?>>Bisexuel</option>
    </select>
</div>
    <div class="col-md-4 mb-3">
         <label for="validationTooltip08">Nouvelle classe :</label>
         <input type="text" name="new_lib_class" value="<?php echo $student['lib_class']; ?>">

    </div>         

    <!-- Ajoutez d'autres champs de formulaire avec les valeurs actuelles -->

    <button class="btn btn-primary" type="submit" name="update">Update information</button>
    <a href="listeleve.php" class="btn btn-primary" style=" margin-left : 20px; margin-right: -10px; margin-bottom: -30 ;    text-align: center;"> list eleves</a>
</div>
 </form>
</div>
</html>