<?php
session_start();
require 'helper.php';

session_unset();
// Détruire toutes les données de session
session_destroy();

// Rediriger vers la page de connexion ou toute autre page appropri
 redirectToLogin();
?>
