<?php
session_start();
include 'connes.php';
include 'helper.php';
include 'panel.php';

$error = '';
$errorMessage = ''; // Nouvelle variable pour stocker les messages d'erreur

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $student_name = $_POST['student_name'];
    $student_surname = $_POST['student_surname'];
    $stud_sexe = $_POST['stud_sexe'];
    $stud_brthd = $_POST['stud_brthd'];
    $student_email = $_POST['student_email'];
    $student_phone = $_POST['student_phone'];
    $classe = $_POST['classe'];

    // Vérification si toutes les entrées sont remplies
    if (empty($student_name) || empty($student_surname) || empty($stud_sexe) || empty($stud_brthd) || empty($student_email) || empty($student_phone) || empty($classe)) {
        $errorMessage = 'Veuillez remplir toutes les informations.';
    } else {
        // Connexion à la base de données
        $database = new PDO("mysql:host=localhost;dbname=iskoul", "root", "");

        // Vérification de l'unicité de l'email et du téléphone
        $stmt = $database->prepare('SELECT COUNT(*) FROM students WHERE student_email = :student_email OR student_phone = :student_phone');
        $stmt->bindParam(':student_email', $student_email);
        $stmt->bindParam(':student_phone', $student_phone);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $errorMessage = 'L\'email ou le téléphone existe déjà. Veuillez utiliser des informations uniques.';
        } else {
            // Insertion des données dans la table des élèves
            $stmt = $database->prepare('INSERT INTO students (student_name, student_surname, stud_sexe, stud_brthd, student_email, student_phone, id_class) VALUES (:student_name, :student_surname, :stud_sexe, :stud_brthd, :student_email, :student_phone, :classe)');
            $stmt->bindParam(':student_name', $student_name);
            $stmt->bindParam(':student_surname', $student_surname);
            $stmt->bindParam(':stud_sexe', $stud_sexe);
            $stmt->bindParam(':stud_brthd', $stud_brthd);
            $stmt->bindParam(':student_email', $student_email);
            $stmt->bindParam(':student_phone', $student_phone);
            $stmt->bindParam(':classe', $classe);

            try {
                $stmt->execute();
                $errorMessage = 'Élève enregistré avec succès.';
            } catch (PDOException $e) {
                // Gestion des erreurs d'insertion
                $errorMessage = 'Erreur d\'insertion : ' . $e->getMessage();
            }
        }
    }
}

$database = new PDO("mysql:host=localhost;dbname=iskoul", "root", "");
// Récupération des classes depuis la base de données
$stmtClasses = $database->query('SELECT id_class, lib_class FROM classes');
$classes = $stmtClasses->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription des élèves</title>
</head>
<body>
    <style>
        .error-message {
            color: red;
            margin-top: -10px;
        }
    </style>
  
<div id="right-panel" class="right-panel">
    <p class="error-message"><?php echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . $errorMessage . '</div>' ?></p>

 <form class="needs-validation" novalidate action="" method = "POST" style = "box-sizing: border-box;">
  <div class="form-row" style="  margin-left: 5px; margin-right: 5px;     margin-bottom: -30; ">

   <div class="col-md-4 mb-3">
        <label for="student_name">Nom :</label>
        <input type="text" class="form-control" name="student_name" value="<?php echo isset($_POST['student_name']) ? $_POST['student_name'] : ''; ?>"><br>
   </div>    

     <div class="col-md-4 mb-3">
        <label for="student_surname">Prénom :</label>
        <input type="text" class="form-control" name="student_surname" value="<?php echo isset($_POST['student_surname']) ? $_POST['student_surname'] : ''; ?>"><br>
     </div>  
   
      <div class="col-md-4 mb-3">
        <label for="stud_sexe">Sexe :</label>
        <select name="stud_sexe" class="form-control" required>
            <option value="masculin">Masculin</option>
            <option value="feminin">Féminin</option>
            <option value="homo">Homo</option>
        </select><br>
      </div>
   
       <div class="col-md-4 mb-3">
        <label for="student_email">Email :</label>
        <input type="email" class="form-control" name="student_email" value="<?php echo isset($_POST['student_email']) ? $_POST['student_email'] : ''; ?>"><br>
       </div>

     <div class="col-md-4 mb-3">
        <label for="stud_brthd">Date de naissance :</label>
        <input type="date" class="form-control" name="stud_brthd" value="<?php echo isset($_POST['stud_brthd']) ? $_POST['stud_brthd'] : ''; ?>"><br>
     </div>  

     <div class="col-md-4 mb-3">
        <label for="student_phone">Téléphone :</label>
        <input type="text" class="form-control" name="student_phone" required><br>
     </div>   

      <div class="col-md-4 mb-3">
        <label for="classe">Classe :</label>
        <select name="classe" class="form-control"value=" <?php echo $classe; ?>">
            <?php foreach ($classes as $cl) : ?>
                <option value="<?php echo $cl['id_class']; ?>"><?php echo $cl['lib_class']; ?></option>
            <?php endforeach; ?>
        </select><br>
      </div> 
      
        <input type="submit"  value="Inscrire">
    </form>
</body>
</html>
