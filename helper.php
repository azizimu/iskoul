<?php
if (!function_exists('isConnected')) {
function isConnected() {
    return isset($_SESSION['is_connected']) && $_SESSION['is_connected'] === true;
}
}
function redirectToLogin() {
    header("Location: login.php");
    exit();
}

function redirectToHome() {
    header("Location: index.php");
    exit();
}

function redirectTolist() {
    header("Location: listeel.php");
    
}

function redirectTolistel() {
    header("Location: listeleve.php");
   
}
// Fonction pour ajouter une erreur
function addError(&$error, $message)
{
    $error .= $message . '<br>';
}

?>
