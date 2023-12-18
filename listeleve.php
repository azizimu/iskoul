<?php
session_start();
include 'connes.php';
include 'helper.php';
include 'panel.php';  

$pdo = new PDO("mysql:host=localhost;dbname=iskoul", "root", "");

// Récupérer les élèves et leurs paramètres avec le nom de la classe
$sql = "SELECT students.*, classes.lib_class 
        FROM students 
        LEFT JOIN classes ON students.id_class = classes.id_class";
$stmt = $pdo->query($sql);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdo = null;
?>

<html>
<head>
    <!-- Les balises head vont ici -->
</head>
<body>
    <div id="right-panel" class="right-panel">
        <div class="card-body--">
            <a href="Eleves.php" class="btn btn-blue mb-3" style= background-color:#6FB4FF  >Add New</a>
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                      
                        <th>matricule</th>
                        <th>student_name</th>
                        <th>student_surname</th>
                        <th>email</th>
                        <th>telephone</th>
                        <th>birth</th>
                        <th>sexe</th>
                        <th>classe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                   foreach ($students as $row) {
                     echo "<tr>";
                       echo "<td>" . $row['matricule'] . "</td>";
                       echo "<td>" . $row['student_name'] . "</td>";
                       echo "<td>" . $row['student_surname'] . "</td>";
                       echo "<td>" . $row['student_email'] . "</td>";
                       echo "<td>" . $row['student_phone'] . "</td>";
                       echo "<td>" . $row['stud_brthd'] . "</td>";
                       echo "<td>" . $row['stud_sexe'] . "</td>";
                       echo "<td>" . $row['lib_class'] . "</td>";
                       echo "<td>
                       <a href='edit.php?id=" . $row['matricule'] . "' class='link-dark' style='color: #007bff;'><i class='fa-solid fa-pen-to-square fs-5 me-3 style='background-color: blue'></i> edit </a>
                       <a href='deleteeleve.php?id=" . $row['matricule'] . "' class='link-dark' style='color: #dc3545;'><i class='fa-solid fa-trash fs-5'>delete</i></a>
                       </td>";
                    echo "</tr>";
                   

                    }
                    ?>
                </tbody>
            </table>
        </div> <!-- /.card-body -->
    </div>
</body>
</html>
