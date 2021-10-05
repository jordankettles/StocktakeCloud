<?php

    session_start();
        
    // Check if the user is logged in, otherwise redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: signing/login.php");
        exit;
    }

    require_once('../config.php');

    # Get the product to be deleted from the HTML POST
    $product_to_delete = explode('|', $_REQUEST['product']);
    $product_name = $product_to_delete[0];
    $desired_quantity = $product_to_delete[1];
    

    # Loops through all tables and deletes the product that matches.
    # Note this method depends on there being a unique name and desired_quantity for each product
    # across all tables, so if there are multiple records with the same attributes in seperate tables
    # they will all be deleted.
    $tables = array("Spirits", "Wine", "Beer", "NonAlc"); # Tables in the database
    foreach($tables as $table) { 
        $query = "DELETE FROM $table WHERE name='$product_name' AND desired_quantity=$desired_quantity AND adminID=" . $_SESSION['id'];
        $pdo->exec($query);
    }

    echo "<script>location.href='../index.php'</script>"; # Return to admin page 
?>