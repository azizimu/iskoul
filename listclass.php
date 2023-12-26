<?php
session_start();
include 'connes.php';
include 'helper.php';
include 'panel.php';

$error = null;
$success = null;

$pdo = new PDO("mysql:host=localhost;dbname=iskoul", "root", "");

// Récupérer la liste des classes avec les noms des enseignants depuis la base de données
$stmt = $pdo->query("SELECT classes.*, professors.prof_name 
                    FROM classes 
                    LEFT JOIN professors ON classes.num_prof = professors.num_prof");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    <h3>Liste des classes enregistrées :</h3>
    <table class="table table-hover text-center">
        <thead>
            <tr>
                <th>Classe</th>
                <th>Année Scolaire</th>
                <th>Enseignant Responsable</th>
                <th>action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classes as $class) : ?>
                <tr>
                    <td><?php echo $class['lib_class']; ?></td>
                    <td><?php echo $class['an_scolair']; ?></td>
                    <td><?php echo $class['prof_name']; ?></td>
                    <td>
                <a href='deleteclass.php?id_class=<?php echo $class['id_class']; ?>' class='link-dark' style='color: #dc3545;'>
                <i class='fa-solid fa-trash fs-5'></i> Delete
                </a>
             </td>
            </tr>
            <?php endforeach; ?>
          
                   
        </tbody>
    </table>
</div>

</html>
