<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Stocktake Record #<?php echo $_REQUEST['id']?></title>
        <link rel="stylesheet" href="../style.css" type="text/css">
    </head>

    <body>
        <?php

            ## DB LOGIN
            $db_host   = 'database-1.crx8snaug9em.us-east-1.rds.amazonaws.com'; # Change this to RDS instance endpoint.
            $db_name   = 'stocktake';
            $db_user   = 'database1';
            $db_passwd = 'database-1'; # Change this too.

            $pdo_dsn = "mysql:host=$db_host;port=3306;dbname=$db_name";

            $pdo = new PDO($pdo_dsn, $db_user, $db_passwd);

            # Get the id of the stocktake instance selected from the POST button from HTML 
            $stock_num = $_REQUEST['id'];

            # Select the products and display them
            $q = $pdo->query("SELECT * FROM StocktakeProds WHERE stocktake_num=$stock_num");
            
            echo "<h1>Stocktake Record #$stock_num</h1>\n
                    <table id='record'>\n
                    <tr>\n
                    <th>Name</th>
                    <th>Desired Quantity</th>
                    <th>Current Quantity</th>
                    </tr>";

            while ($row = $q->fetch()) {
                echo '<tr><td>';
                echo $row['name'];
                echo '</td><td>';
                echo $row['desired_quantity'];
                echo '</td><td>';
                if ($row["current_quantityInt"] == NULL) {
                    echo $row["current_quantityDec"];
                } else {
                    echo $row["current_quantityInt"];
                }
                echo '</td></tr>';
            }
            echo "</table>\n";
        ?>
    </body>
</html>

