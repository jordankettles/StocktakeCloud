<?php

    ## DB LOGIN
    $db_host   = 'database-1.crx8snaug9em.us-east-1.rds.amazonaws.com'; # Change this to RDS instance endpoint.
    $db_name   = 'stocktake';
    $db_user   = 'database1';
    $db_passwd = 'database-1'; # Change this too.

    $pdo_dsn = "mysql:host=$db_host;port=3306;dbname=$db_name";

    $pdo = new PDO($pdo_dsn, $db_user, $db_passwd);

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
        $query = "DELETE FROM $table WHERE name='$product_name' AND desired_quantity=$desired_quantity";
        $pdo->exec($query);
    }

    echo "<script>location.href='../index.php'</script>"; # Return to admin page 
?>