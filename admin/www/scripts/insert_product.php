<?php

    ## DB LOGIN
    $db_host   = 'database-1.crx8snaug9em.us-east-1.rds.amazonaws.com'; # Change this to RDS instance endpoint.
    $db_name   = 'stocktake';
    $db_user   = 'database1';
    $db_passwd = 'database-1'; # Change this too.

    $pdo_dsn = "mysql:host=$db_host;port=3306;dbname=$db_name";

    $pdo = new PDO($pdo_dsn, $db_user, $db_passwd);

    ## Must have these
    $product_name = $_REQUEST['name'];
    $desired_quantity = $_REQUEST['dq'];
    $product_category = $_REQUEST['category'];

    ## Optional
    $product_volume = $_REQUEST['volume'];
    $product_fullWeight = $_REQUEST['full_weight'];
    $product_emptyWeight = $_REQUEST['empty_weight'];

    # First insert record into the respective table with the must have values
    $sql = "INSERT INTO $product_category (name, desired_quantity) VALUES ('$product_name', $desired_quantity)";
    $pdo->exec($sql);

    # Then check for the optional values existing, if so update the record created in the previous step
    if (!empty($product_volume)) { 
        $sql = "UPDATE $product_category SET volume=$product_volume WHERE name='$product_name'";
        $pdo->exec($sql);
    }
    if (!empty($product_fullWeight)) {
        $sql = "UPDATE $product_category SET full_weight=$product_fullWeight WHERE name='$product_name'";
        $pdo->exec($sql);
    }
    if (!empty($product_emptyWeight)) {
        $sql = "UPDATE $product_category SET empty_weight=$product_emptyWeight WHERE name='$product_name'";
        $pdo->exec($sql);
    }

    echo "<script>location.href='../index.php'</script>"; #Return to index page
?>