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

The client webserver runs on an EC2 instance, features a login system for which the client signs up an account (username and password). The client website can:

- Request access to their business's stocktake database by entering the username of the admin which has control over the database. Once access has been granted, the client can:
  - Read all the products and desired quantities stored in the database.
  -	Write new stocktakes to the database using ‘submit_stocktake.php’.
 

### Admin webserver

The Admin webserver runs on an EC2 instance and features a login system like the Client webserver. The admin website can:
-	Read the products and all related information in the database such as volume and weights.
-	Read all previous stored stocktakes in the database using ‘records.php’.
-	Delete stocktake products from the database using ‘delete_product.php’.
-	Add new stocktake products to the database using ‘insert_product.php’.
-	Allow a client access to their business's database.
-	Remove client access to their business's database.
-	Enter the email for notifications using SNS.


### RDS instance

All storage is provided by an Amazon Relational Database Service (or RDS) using MySQL, which has all stocktake related tables as well as tables for the Admin and Client users.

### SNS

The client site publishes a message to the SNS Topic each time a new stocktake is performed. The admin site can unsubscribe and subscribe email accounts to the topic. An example message that a subscribed email might recieve looks like this:
-	`A new stocktake #13 was submitted by user cosc349client at 21-10-06 20:21:37.`

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


#### Elastic IPs

For your convenience, you can create two Elastic IPs for your EC2 instances. You can skip this step and comment out the elastic ip line in the Vagrantfile, but once you `vagrant up`, you will have to navigate to your EC2 console to get the Public IPv4 DNS link for both of your EC2 instances to access them through a web browser. To generate two Elastic IPs, begin by navigating to your EC2 console on AWS.

- In the left hand menu, select Elastic IPs under Network & Security.
- Click Allocate Elastic IP Address
- Click Allocate
- Repeat
- Now you should see two Elastic IPs associated with your account.

#### Subnet ID

To find the Subnet ID of your availability zone (we are using us-east-1a), use the AWS CLI and enter this command.

- `aws ec2 describe-subnets --region us-east-1`

Note the SubnetID.

#### Security Group
We used the default VPC and default security group that comes with it, however we changed the inbound rules to

<img width="1437" alt="Screen Shot 2021-10-08 at 12 48 00 AM" src="https://user-images.githubusercontent.com/70932357/136502741-ba5ee620-aa74-4150-865d-97bf48c35d1b.png">

Take note of the security group ID 
#### RDS Instance

Navigate to the RDS page on your AWS console and create a new RDS with the following settings:

![Screen Shot 2021-10-08 at 6 26 02 PM](https://user-images.githubusercontent.com/70932357/136502978-7aa72c35-52c2-43ba-a915-d55a3ea9bf0c.png)

Feel free to use your own database username/passwords, take note of both of these as well as the endpoint.

#### SNS Topic

To create a new SNS Topic, begin by navigating to the Simple Notification Service Dashboard on AWS. 

- In the left hand menu, select Topics.
- Click Create Topic.
- Select Standard Type.
- Give the topic a name. This name will be seen in notification emails.
- Click create Topic and the bottom of the page.
- Note the ARN of your new SNS Topic.

Also add sns keys here.

#### Installation

To begin installing our application, you will first need to clone the repo.

- `git clone https://github.com/jordankettles/StocktakeCloud`

Once you have successfully cloned the repo, cd into the repository.

- `cd StocktakeCloud`


<!-- Now you will need to enter details specific to your AWS account into the `.aws/credentials file`. For AWS educate accounts:
- Sign in to your educate account here https://www.awseducate.com/signin/SiteLogin
- Navigate to the "My Classrooms" tab located in the top right corner
- Click on "Go to Classroom", this should take you to your "Workbench" page
- Click on "Account details" and then AWS CLI: [Show]
- Copy and paste the details found here to `StocktakeCloud/.aws/credentials` -->
Depending on your account type you will need to get your AWS credentials, for educate accounts:
- Sign in to your educate account here https://www.awseducate.com/signin/SiteLogin
- Navigate to the "My Classrooms" tab located in the top right corner
- Click on "Go to Classroom", this should take you to your "Workbench" page
- Click on "Account details" and then AWS CLI: [Show]

For other account types you will have to look into how to find these yourself.

Now we need to set up a script to be run before launching the EC2-instances using Vagrant, Mac / Unix users this file is a shell script (`.sh`) found in the `StocktakeCloud/setup` directory, for Windows users this file is a batch (`.bat`) and replaces the `vagrant` command.

- Enter your `aws_access_key_id`, `aws_secret_access_key` and the `aws_session_token` to the variables of the same name (but capitalised).
- Enter your `sns_key` and `sns_secret` values pertaining to your SNS instance.
- Enter the `keypair_name` and `private_key_path` that you downloaded previously. If you haven't done this before, navidate to "Key pairs" under "Network and security" in your EC2 management console and generate a keypair, give it a name (`keypair_name`) and download the .pem file and place this in `~/.ssh`, then `private_key_path = ~/.ssh/<keypair_name>`.
- Enter the two Elastic IPs you created.
- Enter the security group id used for which the EC2 instances and the RDS are setup with
- Enter your Subnet ID

#### RDS Database Initialisation

With your RDS setup and running, you now need to setup the correct tables for the App. This is done within the webserverAdmin provisioning in the Vagrantfile, to set this up correctly do the following:
- You need to change all forms of the command `mysql -h <endpoint> -P <port> -u <username>` to suit your RDS instance. You also need to change the `export   MYSQL_PWD=<password>`. These can both be found in the provisioning script of webserverAdmin, as well as the `:destroy` trigger found at ~line 86. 

- Finally, change both `config.php` files within `/admin` and `/client` with your correct RDS values
![Screen Shot 2021-10-08 at 6 45 50 PM](https://user-images.githubusercontent.com/70932357/136504633-b729705f-fb4a-45a9-87c0-8623864a805d.png)

#### Vagrant 
Now you can start the application. If you are on MacOS, run `source setup/aws.sh` to export the environment variables set earlier and run `vagrant up`. For Windows users, all you need to do is run `vg.bat` (assuming you have entered the values in). Note you will see red text mentioning Composer 2 should be used instead of Composer 1, you can ignore this.


You now should have successfully installed and started the application. To access the websites login to your AWS and navigate to the EC2 Management console, you should see two instances running. Click on these instances then click on the "Open address" next to the Public IPv4 address. Note that this will open up an `https://<public IPv4>` however our Apache 2 isn't set up for `https`, so click on the url and make it http like `http://<public IPv4>`. You should now see a login page for both the Client and Admin pages.

To close the application, run the following command in the same directory. 

- `vagrant destroy`

### Use

<!-- To use the application as a client, visit [192.168.2.13](http://192.168.2.13) in a web browser. 

To use the application as an admin, visit [192.168.2.11](http://192.168.2.11) in a web browser.  -->


### Destroying VMs

After making changes to the source code you might want to restart a specific instance rather than both VMs to test your changes. To do so, use the following commands: 
- `vagrant destroy [name]`
- `vagrant up [name]`
