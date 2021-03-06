<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, otherwise redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: signing/login.php");
        exit;
    }

    require_once('config.php');
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Stocktake App Admin Page</title>
        <link rel="stylesheet" href="style.css" type="text/css">

        <script>
            function showOptions(element) {
                var selectedValue = element.options[element.selectedIndex].value;
                if (selectedValue == "Beer" || selectedValue == "NonAlc") {
                    document.querySelectorAll('.each').forEach(function(element) {
                    element.style.display = "none";
                });
                } else {
                    document.querySelectorAll('.each').forEach(function(element) {
                    element.style.display = "inline-block";
                });
                }
            }
        </script>
    </head>

    <body>
        <h1>Stocktake App Admin Page</h1>

        <section id="logout_section">
            <?php
                echo "<p>Welcome, " . $_SESSION["username"] . ".<p>"
            ?>
            <form action="signing/logout.php">
                <input type="submit" value="Logout"/>
            </form>
        </section>

        <p>Welcome to the Admin page for Super Bartenders.</p>

        <h2>Current Stocktake Products</h2>
            <div id="current">
                <h3> Spirits </h3>
                    <table>
                        <tr><th>Product Name</th><th>Volume (ml)</th><th>Full Weight (g)</th><th>Empty Weight (g)</th><th>Desired Quantity</th></tr>
                        <?php
 
                            # Grab all records in Spirits table, Wine table, Beer table, and Non-Alcoholic table.
                            $q_Spirits = $pdo->query("SELECT * FROM Spirits WHERE adminID=" . $_SESSION['id']);
                            $q_Wine = $pdo->query("SELECT * FROM Wine WHERE adminID=" . $_SESSION['id']);
                            $q_Beer = $pdo->query("SELECT * FROM Beer WHERE adminID=" . $_SESSION['id']);
                            $q_NonAlc = $pdo->query("SELECT * FROM NonAlc WHERE adminID=" . $_SESSION['id']);

                            # Displays Spirits
                            while($row = $q_Spirits->fetch()){
                                echo '<tr><td>';
                                echo $row["name"];
                                echo '</td><td>';
                                echo $row["volume"];
                                echo '</td><td>';
                                echo $row["full_weight"];
                                echo '</td><td>';
                                echo $row["empty_weight"];
                                echo '</td><td>';
                                echo $row["desired_quantity"];
                                echo '</td></tr>';
                            }
                            
                            echo '</table>
                                    <h3> Wine </h3>
                                    <table>
                                        <tr><th>Product Name</th><th>Volume (ml)</th><th>Full Weight (g)</th><th>Empty Weight (g)</th><th>Desired Quantity</th></tr>';
                            # Displays Wine
                            while($row = $q_Wine->fetch()){
                                echo '<tr><td>';
                                echo $row["name"];
                                echo '</td><td>';
                                echo $row["volume"];
                                echo '</td><td>';
                                echo $row["full_weight"];
                                echo '</td><td>';
                                echo $row["empty_weight"];
                                echo '</td><td>';
                                echo $row["desired_quantity"];
                                echo '</td></tr>';
                            }
                    
                            echo '</table>
                                    <h3> Beer </h3>
                                    <table>
                                    <tr><th>Product Name</th><th>Desired Quantity</th></tr>';
                            # Displays Beer
                            while($row = $q_Beer->fetch()){
                                echo '<tr><td>';
                                echo $row["name"];
                                echo '</td><td>';
                                echo $row["desired_quantity"];
                                echo '</td></tr>';
                            } 

                            echo '</table>
                                    <h3> Non-Alcoholic </h3>
                                    <table>
                                    <tr><th>Product Name</th><th>Desired Quantity</th></tr>';
                            # Displays Non-Alcoholic
                            while($row = $q_NonAlc->fetch()){
                                echo '<tr><td>';
                                echo $row["name"];
                                echo '</td><td>';
                                echo $row["desired_quantity"];
                                echo '</td></tr>';
                            }
                        ?>
                    </table>
                </div>
        <section class="admin_section">
            <div>
                <h2>Add a new Product</h2>
                <!-- A form to insert a product, this is sent to insert_product.php with the inputs-->
                <form method="post" enctype="aplication/x-www-form-urlencoded" action="scripts/insert_product.php">
                    <fieldset id="add_product">
                        <p id=required_text>* Required Information</p>
                        <label for="name">Name of Product*: </label><input type="text" placeholder="Product Name" id="name" name="name" maxlength="50" required>
                        <label for="type">Product Type*: </label>
                        <select name="category" id="category" onchange="showOptions(this)">
                            <option value="Spirits">Spirits</option>
                            <option value="Wine">Wine</option>
                            <option value="Beer">Beer</option>
                            <option value="NonAlc">NonAlc</option>
                        </select>
                        <label for="volume" class="each">Volume (ml): </label><input type="number" placeholder="Volume" id="volume" class="each" name="volume" min="0">
                        <label for="full_weight" class="each">Full Weight (g): </label><input type="number" placeholder="Full Weight" id="full_weight" class="each" name="full_weight" min="0">
                        <label for="empty_weight" class="each">Empty Weight (g): </label><input type="number" placeholder="Empty Weight" id="empty_weight" class="each" name="empty_weight" min="0">
                        <label for="dq">Desired Quantity*:</label><input type="number" id="dq" name="dq" min="1" max="1000" placeholder="(1-1000)"required>
                        <input type="submit" value="Submit" id="submit_product">
                    </fieldset>
                </form>
            </div>

            <div>
                <h2>Delete a Product</h2>
                <!-- A form to delete a product, this is sent to delete_product.php with the inputs-->
                <form method="post" enctype="aplication/x-www-form-urlencoded" action="scripts/delete_product.php">
                    <fieldset id="delete_product">
                        <select name="product" id="name">
                            <?php
 
                                $sql = "SELECT name, desired_quantity FROM Spirits WHERE adminID=" . $_SESSION['id']
                                . " UNION SELECT name, desired_quantity FROM Wine WHERE adminID=" . $_SESSION['id']
                                . " UNION SELECT name, desired_quantity FROM Beer WHERE adminID=" . $_SESSION['id']
                                . " UNION SELECT name, desired_quantity FROM NonAlc WHERE adminID=" . $_SESSION['id'];
                                $q = $pdo->query($sql);

                                # Display the records which can be selected 
                                while($row = $q->fetch()){
                                echo "<option value='" . $row["name"] . "|" . $row["desired_quantity"] . "'>" 
                                . $row["name"] . ", " . $row["desired_quantity"] . "</option>";
                                }
                            ?>
                        </select>
                        <input type="submit" value="Delete">
                    </fieldset>
                </form>
            </div>
        </section>

        <h2>Previous Stocktakes:</h2>

        <table id="prev_stocktakes">
            <tr><th>Date</th><th>Stocktake ID #</th><th>Go to Stocktake</th></tr>

            <?php

                $q = $pdo->query("SELECT * FROM StocktakeRefs WHERE adminID=" . $_SESSION['id']);

                # Display each record
                while($row = $q->fetch()){
                echo '<tr><td>';
                echo $row['dt'];
                echo '</td><td>';
                echo $row['stock_num'];
                echo '<td><a href="scripts/records.php?id='.$row['stock_num'].'"><input type="submit" name="submit" 
                value="Open" class="Register" /></a></td>';
                }
            ?>
        </table>
        <section class="users_section">
            <div>
                <h2>User Access Requests</h2>
                <table class="users_table">
                    <tr><th>Client Username</th><th>Allow Access</th></tr>

                    <?php 
                        $q = $pdo->query("SELECT * FROM ClientRequests WHERE adminID=" . $_SESSION['id']);
                        while ($row = $q->fetch()) {
                            echo '<tr><td>';
                            echo $row['clientUsername'];
                            echo '<td><a href="scripts/allow_access.php?id='.$row['clientID'].'"><input type="submit" name="submit"
                            value="Allow" class="Register" /></a></td>';
                        }
                    ?>
                </table>
            </div>

            <div>

                <h2>Current Users</h2>
                <table class="users_table">
                    <tr><th>Client Username</th><th>Remove Access</th></tr>

                    <?php 
                        $q = $pdo->query("SELECT * FROM ClientTableAccess WHERE adminID=" . $_SESSION['id']);
                        $clients = array();

                        while ($row = $q->fetch()) {
                            $clients[$row['clientID']] = $pdo->query("SELECT * FROM ClientUsers WHERE id=" . $row['clientID'])->fetch()['username'];
                        }
                        $q = $pdo->query("SELECT * FROM ClientTableAccess WHERE adminID=" . $_SESSION['id']);

                        while ($row = $q->fetch()) {
                            echo '<tr><td>';
                            echo $clients[$row['clientID']];
                            echo '<td><a href="scripts/remove_access.php?id='.$row['clientID'].'"><input type="submit" name="submit"
                            value="Remove" class="Register" /></a></td>';
                        }
                    ?>
                </table>
            </div>
        </section>

        <div>
            <h2>Update Email Address</h2>
            <form id="change_email" method="post" enctype="aplication/x-www-form-urlencoded" action="scripts/change_email.php">
                    <label for="email">Email*: </label><input type="email" placeholder="Email" id="email" name="email" required>
                    <input id="submit_email" type="submit" value="Submit">
            </form>
        </div>
    </body>
</html>