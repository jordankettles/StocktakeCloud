<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, otherwise redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: signing/login.php");
        exit;
    }
    // Include config file
    require_once "../config.php";
    $adminUsername = $_REQUEST['adminUsername'];

    $q = $pdo->query("SELECT * FROM AdminUsers WHERE username='$adminUsername'");

    $row = $q->fetch();
    $clientUsername = $_SESSION['username'];
    $clientID = $_SESSION['id'];
    $adminUsername = $row['username'];
    $adminID = $row['id'];

    if (!empty($row['username'])) {
        try {
            $pdo->exec("INSERT INTO ClientRequests VALUES ('$clientUsername', $clientID, '$adminUsername', $adminID)");
        }catch(PDOException $e){
            // request already exists, go back to index
            // header("location: ../index.php");
        }
    }
    header("location: ../index.php");
?>