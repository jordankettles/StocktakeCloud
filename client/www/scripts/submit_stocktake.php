<?php
    require '/vagrant/vendor/autoload.php';

    use Aws\Sns\SnsClient;
    use Aws\Exception\AwsException;
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, otherwise redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: ../signing/login.php");
        exit;
    }
    // Include config file
    require_once "../config.php";

    try {
        $adminID = $pdo->query("SELECT * FROM ClientTableAccess WHERE clientID=" . $_SESSION['id'])->fetch()['adminID'];


        if (!empty($adminID)) {
        # Find the max stocktake_num and set new num to max+1 
            $max = $pdo->query("SELECT MAX(stocktake_num) FROM StocktakeProds WHERE adminID=$adminID")->fetch()[0];
            $stock_num = 0;
            if ($max == NULL) {
                $stock_num = 1;
            } else {
                $stock_num = $max + 1;
            }

            # Inserts into stocktake prods the current counts that were recorded from stocktake.
            # $_REQUEST is an associative array with keys for each table,
            # within these is an array of keys for the product id for it's table,
            # which has it's current count recorded in the stocktake.
            # 
            # e.g $_REQUEST['Spirits']['id'] = current_count for that id in Spirits
            $tables = array("Spirits", "Wine", "Beer", "NonAlc"); # Product tables 
            foreach ($tables as $table) {
                $values = $_REQUEST[$table]; # an associative array with keys = "id", values = current_counts

                foreach ($values as $id => $count) { #$id = product id for that table, $count = current_count 
                    $rec = $pdo->query("SELECT * FROM $table WHERE id=$id AND adminID=$adminID")->fetch();

                    # Enter a decimal current count for spirits/wine
                    if ($table == "Spirits" || $table == "Wine") {
                        $insert = "INSERT INTO StocktakeProds (name, desired_quantity, current_quantityDec, stocktake_num, adminID) VALUES ('$rec[name]', '$rec[desired_quantity]', '$count', '$stock_num', $adminID)";
                        $pdo->exec($insert);
                    } else { # Either Beer or NonAlc, in both cases we want an integer current count 
                        $insert = "INSERT INTO StocktakeProds (name, desired_quantity, current_quantityInt, stocktake_num, adminID) VALUES ('$rec[name]', '$rec[desired_quantity]', '$count', '$stock_num', $adminID)";
                        $pdo->exec($insert);
                    }
                }
            }
            
            ### Add the reference to stocktake_refs
            # This can be changed dependent on where the user is / their date-time preference
            date_default_timezone_set('Pacific/Auckland');
            $date = date('y-n-d H:i:s');

            # This references the products we just inserted into StocktakeProds with the same $stock_num
            $pdo->exec("INSERT INTO StocktakeRefs VALUES ('$date', $stock_num, $adminID)");

        }
    }catch(PDOException $e){
        die("ERROR: Could not connect. " . $e->getMessage());
    }

    try {
        #Send SNS message.
        # I used the aws php sdk examples github repo as a guide.
        # https://github.com/awsdocs/aws-doc-sdk-examples/tree/main/php/example_code/sns
        $SnSclient = new SnsClient([
            'profile' => 'default',
            'region' => 'us-east-1',
            'version' => '2010-03-31'
        ]);

        $message = 'A new stocktake #' . $stock_num .' was submitted by user ' . $_SESSION['username'] . ' at ' . $date . '. ';
        $topic = 'arn:aws:sns:us-east-1:898431862435:StocktakeCloud';

        $result = $SnSclient->publish([
            'Message' => $message,
            'TopicArn' => $topic,
        ]);
    } 
    catch (AwsException $e) {
        // output error message if fails
        error_log($e->getMessage());
    }

    # Return to stocktake page
    echo "<script>location.href='../.'</script>";
?>





    
