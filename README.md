# StocktakeCloud

<!-- ## Assignment Description
- Application should be built using at least 2 VM's interacting
  - 2x EC2 instances
    - Webservers
  - 2X non-EC2 cloud services
    - Database
    - Notication service (maybe)
    - [How to Create a RDS Notification](https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/USER_Events.overview.html)
    - [How to subscribe an email to Notifications](https://docs.aws.amazon.com/sns/latest/dg/sns-email-notifications.html)
    - User Credentials login for database (Cognito maybe)



 -->
<!-- # Stocktake
 -->
A web application to aid in the **stocktake** process for bars. 
We use 2 AWS-EC2 instances to host both an admin and client webservers, AWS-RDS to host a database and an AWS-SNS service for email notifications.

### Contents

[Description](#description)

[AWS](#aws)

[Installation and Usage](#installation-and-usage)

## Description

### 1. What are stocktakes?

Stocktakes are a fundamental component of running a hospitality business. Their purpose is to track *ingredients/items* that the business uses on their premesis that are involved in the process of *creating a product* for the customer (e.g, cocktails, meals), which collectively we refer to as **stock**.

For each stocktake, each *item* will have a *desired quantity* for which the *current quantity* of that item is to maintained at. The measurement performed for each item depends on it's type, e.g, beer cans/bottles are counted individually and have an integer domain, spirits are measured by their current volume (either full, %full, or empty) by weighing the bottle and their domain is decimal. 

### 2. What can we do to aid this process?

Our aim is to provide tools that are useful to the stocktaking process. The tools that are desirable for the person performing the stocktake are different to the tools that would be used by the owner of the business. 

### 3. Tools for the manager

Our aim is provide as much automation as possible for the manager during the stocktaking process:

- Provide a table of products which the manager would refer to when entering the current level of a product, which each product in the table having a desired quantity
- When the stocktake is initiated, the manager enters the current total quantity of each product.
  - If the product is counted indiviually, then an integer can be entered.
  - If the product is measured in volume, then amount of full products + the ratio of the currently in-use product can be entered.
  - The manager can use the calculator to work out the ratio of current in-use products.
- A secure login system for which they use to access their respective business's stocktake database.

### 4. Tools for the owner

Our aim is to provide tools for the owner to monitor the stocktakes that have been performed:

- Access to view all information about current stocktake products.
- Access to all stocktakes that have been performed which will be marked with a date.
- Add/remove current stocktake products.
- Assign desired quantity to products.
- Assign weights to products measured by volume.
- A secure login system where they can see what managers/clients have requested access to their stocktake database.
- // SNS email notifications

## AWS

### Client webserver

The Client webserver runs on an EC2 instance, features a login system for which the client signs up an account (username and password). The Client website can:

- Request access to their business's stocktake database by entering the username of the admin which has control over the database. Once access has been granted, the client can:
  - Read all the products and desired quantities stored in the database.
  -	Write new stocktakes to the database using ‘submit_stocktake.php’.
 

### Admin webserver

The Admin webserver runs on an EC2 instance and features a login system like the Client webserver. The admin website can:
-	Read the products and all related information in the database such as volume and weights.
-	Read all previous stored stocktakes in the database using ‘records.php’.
-	Delete stocktake products from the database using ‘delete_product.php’.
-	Add new stocktake products to the database using ‘insert_product.php’.
-	Allow a Client access to their business's database.
-	Enter the email for notifications using SNS


### RDS instance

All storage is provided by an Amazon Relational Database Service (or RDS) using MySQL, which has all stocktake related tables as well as tables for the Admin and Client users.

### SNS

## Installation and Usage

### Requirements

To install our application, you computer will need to support virtualisation. You will need to install the following:
- Vagrant (*v2.2.x*)
  - Built on *v2.2.16* but any *v2.2.x* should be ok <sup>[1](#myfootnote1)</sup>

  - Follow the installation guide for your operating system found [here](https://www.vagrantup.com/docs/installation)
- VirtualBox (*v6.1.x*)
  - Built on *v6.1.26* but any *v6.1.x* 'should' be ok <sup>[1](#myfootnote1)</sup>
  -   Follow the installation guide for your operating system found [here](https://www.virtualbox.org/manual/ch02.html)

- An Amazon Web Services (AWS) account 

<a name="myfootnote1">1</a>: *Other versions have not been tested, so if problems occur please install the same versions the application was built on. If problems still occur, please add the problems to our GitHub Issues.*

### Installation, Starting, and Stopping.

#### AWS Keypairs

#### EC2 Instances

#### RDS Instance

#### SNS Instance


To begin installing our application, you will first need to clone the repo.

- `git clone https://github.com/jordankettles/StocktakeCloud`

Once you have successfully cloned the repo, cd into the repository.

- `cd StocktakeCloud`


Now you will need to enter details specific to your AWS account into the `.aws/credentials file`. For AWS educate accounts:
- Sign in to your educate account here https://www.awseducate.com/signin/SiteLogin
- Navigate to the "My Classrooms" tab located in the top right corner
- Click on "Go to Classroom", this should take you to your "Workbench" page
- Click on "Account details" and then AWS CLI: [Show]
- Copy and paste the details found here to `StocktakeCloud/.aws/credentials`

Now we need to set up a script to be run before launching the EC2-instances using Vagrant, for Windows users this file is a batch (`.bat`) and for Mac users this file is a shell script (`.sh`) found in the `StocktakeCloud/setup` directory 

- Enter your `aws_access_key_id`, `aws_secret_access_key` and the `aws_session_token` contained in your `.aws/credentials` file to the variables of the same name (but capitalised).
- Enter your `sns_key` and `sns_secret` values pertaining to your SNS instance
- Enter the `keypair_name` and `private_key_path` associated with your AWS account
- Enter the elastic IP's used for both EC2 instances to their respective variables
- Enter the security group id used for which the EC2 instances and the RDS are setup with
- Enter the Subnet ID's used for both EC2 instances

#### RDS Database Initialisation

With your RDS setup and running, you now need to setup the correct tables for the App which are found in the `.sql` scripts under `StocktakeCloud/setup/db`.
- Setting up the database requires a MySQL installation, follow the instructions found at https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/ for your OS, for MacOS I recommend using Homebrew (if you don't have this installed see the guide at https://brew.sh/), and the guide for using Homebrew for installing MySQL is found here: https://flaviocopes.com/mysql-how-to-install/.
- With MySQL installed on your system, change your working directory to `StocktakeCloud/setup/db`.
- You should now be able to access your RDS instance with the command `mysql -h <endpoint> -P <port> -u <username> -p`, and enter the password for your database when it prompts you to
- Now that you've accessed your RDS Instance (you should see `>mysql` in the prompt window), run these commands:
  - `CREATE DATABASE stocktake;`
  - `USE STOCKTAKE`
  - `source setup-database.sql`
 - Your database should now be setup to be used by the web application


#### Vagrant 
Now you can start the application.

-  `vagrant up`

You now should have successfully installed and started the application. To close the application, run the following command in the same directory.

- `vagrant destroy`

### Use

<!-- To use the application as a client, visit [192.168.2.13](http://192.168.2.13) in a web browser. 

To use the application as an admin, visit [192.168.2.11](http://192.168.2.11) in a web browser.  -->


### Destroying VMs

After making changes to the source code you might want to restart a specific rather than all three VMs to test your changes. To do so, use the following commands: 
- `vagrant destroy [name]`
- `vagrant up [name]`
