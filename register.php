<?php
session_start();
include 'connes.php';
include 'helper.php';


// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION['is_connected']) && $_SESSION['is_connected']) {
    #redirectToHome();
}

// Initialisation des variables
$error = null;
$user_name= null;
$user_surname = null;
$user_email = null; 
$_SESSION = null;

// Vérification si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    $user_name = $_POST['user_name'];
    $user_surname = $_POST['user_surname'];
    $user_email = $_POST['user_email'];
    $password = $_POST['password'];

    // Validation des champs
    if (!empty($user_name) && !empty($user_surname) && !empty($user_email) && !empty($password)) {
        // Connexion à la base de données
        $database = new PDO("mysql:host=localhost;dbname=iskoul", "root", "");

        // Vérification si l'e-mail est déjà enregistré
        $stmt = $database->prepare("SELECT * FROM utilisateur WHERE user_email = :user_email");
        $stmt->bindParam(':user_email', $user_email);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existingUser) {
            // Insertion de l'utilisateur dans la base de données
            $stmt = $database->prepare("INSERT INTO utilisateur (user_name, user_surname, user_email, password) VALUES (:user_name, :user_surname, :user_email, :password)");
            $stmt->bindParam(':user_name', $user_name);
            $stmt->bindParam(':user_surname', $user_surname);
            $stmt->bindParam(':user_email', $user_email);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                // Utilisateur enregistré avec succès, rediriger vers la page d'accueil
                $_SESSION['is_connected'] = true;
                redirectToHome();
            } else {
                $error = "Erreur lors de l'enregistrement de l'utilisateur.";
            }
        } else {
            $error = "Cet e-mail est déjà enregistré.";
        }
    } else {
        $error = "Veuillez remplir tous les champs du formulaire.";
    }
}

?>



<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>iskoul</title>
    <meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

     

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
          integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
</head>
<body class="bg-dark">

    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index.html">
                        <img class="align-content" src="images/logo.png" alt="">
                    </a>
                </div>
                <div class="login-form" >
                     <form action="" method="POST">
                    <p class="error-message"><?php echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . $error . '</div>' ?></p>
                        <div class="form-group">
                            <label>User Name</label>
                            <input type="text"  name="user_name" placeholder="User Name" style=" margin-left: 30px;width: 310px;" value="<?php echo $user_name; ?>">
                        </div>
                         <div class="form-group">
                            <label>User surname</label>
                            <input type="text"  name="user_surname" placeholder="User Name" style=" width: 310px;" value="<?php echo $user_surname; ?>">
                        </div>
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email"  name="user_email" placeholder="Email" style=" width: 310px;" value="<?php echo $user_email; ?>">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password"placeholder="Password" style=" margin-left: 30px; height: 30px;border-right-width: 2px;width: 308px;">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Agree the terms and policy
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30" name="register">Register</button>
                        <div class="register-link m-t-15 text-center">
                            <p>Already have account ? <a href="login.php"> Sign in</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   

</body>
</html>
