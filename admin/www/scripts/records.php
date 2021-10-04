<?php 
session_start();
        
    // Check if the user is logged in, otherwise redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: signing/login.php");
        exit;
    }

    require_once('../config.php');
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Stocktake Record #<?php echo $_REQUEST['id']?></title>
        <link rel="stylesheet" href="../style.css" type="text/css">
    </head>

    <body>
        <?php

            # Get the id of the stocktake instance selected from the POST button from HTML 
            $stock_num = $_REQUEST['id'];

            # Select the products and display them
            $q = $pdo->query("SELECT * FROM StocktakeProds WHERE stocktake_num=$stock_num AND adminID=" . $_SESSION['id']);
            
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

