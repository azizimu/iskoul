<?php
session_start();
include 'connes.php';
include 'helper.php';
include 'panel.php';  

$database = new connec();
$bd = $database->getConnection();
$bd_name = 'iskoul';
$bd->exec("USE $bd_name");

$query = "SELECT * FROM professors";
$statement = $bd->prepare($query);
$statement->execute();

$results = $statement->fetchAll(PDO::FETCH_OBJ);
?>

<html>
<head>
    <!-- Les balises head vont ici -->
</head>
<body>
    <div id="right-panel" class="right-panel">
        <div class="card-body--">
            <a href="Professeurs.php" class="btn btn-blue mb-3" style= background-color:#6FB4FF  >Add New</a>
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                      
                        <th>num_prof</th>
                        <th>prof_name</th>
                        <th>prof_surname</th>
                        <th>email</th>
                        <th>telephone</th>
                        <th>matieres</th>
                        <th>sexe</th>
                        <th>grade</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($results as $row) {
                        echo "<tr>";
                        echo "<td>" . $row->num_prof . "</td>";
                        echo "<td>" . $row->prof_name . "</td>";
                        echo "<td>" . $row->prof_surname . "</td>";
                        echo "<td>" . $row->prof_email . "</td>";
                        echo "<td>" . $row->prof_phone . "</td>";
                        echo "<td>" . $row->prof_matiere . "</td>";
                        echo "<td>" . $row->prof_sexe . "</td>";
                        echo "<td>" . $row->prof_grade . "</td>";
                        echo "<td>
                            <a href='edit.php?id=" . $row->num_prof . "' class='link-dark'style='color: #007bff;'><i class='fa-solid fa-pen-to-square fs-5 me-3 style = 'background-colors = blue '></i> edit </a>
                            <a href='delete.php?id=" . $row->num_prof . "' class='link-dark'style='color: #dc3545;'><i class='fa-solid fa-trash fs-5'>delete</i></a>
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
