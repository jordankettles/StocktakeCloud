<?php

    session_start();
        
    // Check if the user is logged in, otherwise redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: signing/login.php");
        exit;
    }

    require_once('../config.php');

    $clientID = $_REQUEST['id'];
    $adminID = $_SESSION['id'];

    $pdo->exec("DELETE FROM ClientTableAccess WHERE clientID=$clientID");

    header("location: ../index.php");

?>