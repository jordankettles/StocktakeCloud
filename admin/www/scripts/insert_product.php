<?php
    session_start();
        
    // Check if the user is logged in, otherwise redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: signing/login.php");
        exit;
    }

    require_once('../config.php');

    ## Must have these
    $product_name = $_REQUEST['name'];
    $desired_quantity = $_REQUEST['dq'];
    $product_category = $_REQUEST['category'];

    ## Optional
    $product_volume = $_REQUEST['volume'];
    $product_fullWeight = $_REQUEST['full_weight'];
    $product_emptyWeight = $_REQUEST['empty_weight'];

    # First insert record into the respective table with the must have values
    $sql = "INSERT INTO $product_category (name, desired_quantity, adminID) VALUES ('$product_name', $desired_quantity," . $_SESSION['id'] .")";
    $pdo->exec($sql);

    # Then check for the optional values existing, if so update the record created in the previous step
    if (!empty($product_volume)) { 
        $sql = "UPDATE $product_category SET volume=$product_volume WHERE name='$product_name' AND adminID=" . $_SESSION['id'];
        $pdo->exec($sql);
    }
    if (!empty($product_fullWeight)) {
        $sql = "UPDATE $product_category SET full_weight=$product_fullWeight WHERE name='$product_name' AND adminID=" . $_SESSION['id'];
        $pdo->exec($sql);
    }
    if (!empty($product_emptyWeight)) {
        $sql = "UPDATE $product_category SET empty_weight=$product_emptyWeight WHERE name='$product_name' AND adminID=" . $_SESSION['id'];
        $pdo->exec($sql);
    }

    echo "<script>location.href='../index.php'</script>"; #Return to index page
?>