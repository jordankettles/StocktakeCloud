<?php
// This code is taken from Tutoail Repulic's tutorial on implementing a PHP MySQL login system,
// more can be found here: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php


    ## Database credentials 
    $db_host   = 'database-2.crx8snaug9em.us-east-1.rds.amazonaws.com'; # Change this to RDS instance endpoint.
    $db_name   = 'stocktake';
    $db_user   = 'database2'; # Change to your username for your RDS
    $db_passwd = 'database2'; # Change to your password for your RDS

    $pdo_dsn = "mysql:host=$db_host;port=3306;dbname=$db_name";

    /* Attempt to connect to MySQL database */
    try{
        // $pdo_dsn = "mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME;
        $pdo = new PDO($pdo_dsn, $db_user, $db_passwd);
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        die("ERROR: Could not connect. " . $e->getMessage());
    }
?>