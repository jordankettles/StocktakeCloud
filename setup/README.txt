# Database

Assuming an RDS instance is up and running, we need to access it via mysql to create the stocktake database.
The RDS should have a DB identifier (username), a password and an endpoint.

1. First enter the DB identifier and password into the user and password fields of dbconf.cf respctively.

2. Copy this file to ~/.my.cf and secure this file with chmod 600.

3. You should now be able to access your RDS instance with the command:
        mysql -h <endpoint> -P 3306 -u <user>

4. For first time setup first create the stocktake database and change to it using:
        CREATE database stocktake;
        USE stocktake;

5. Then (making sure you are in the db directory) call the sql scripts to initialse the database:
        source setup-database.sql;
        source demo-values; # If desired

6. Great! Now the database has been initialised, if you want to revert to this initialisation you will need 
   to delete the database and go back to step 4. To logout of mysql, use:
        exit
        
