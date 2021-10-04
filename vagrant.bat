:: Run this script from powershell instead of running vagrant in Windows.
:: To run vagrant up, type .\vagrant.bat up
:: To run vagrant status, type .\vagrant.bat status
:: To run vagrant destroy, type .\vagrant.bat destroy
:: Need to fill out the value fields with your own values.
:: Make sure that your AWS credentials have not expired if you have a problem.

::-----------------------------------------------------------#
:: AWS credentials

set AWS_ACCESS_KEY_ID=blank
set AWS_SECRET_ACCESS_KEY=blank
set AWS_SESSION_TOKEN=blank

::-----------------------------------------------------------#
:: Keypair 

:: Enter the keypair name associated with your aws account
set KEYPAIR_NAME=blank

:: Enter the keypair path on your machine
set PRIVATE_KEY_PATH=blank

::-----------------------------------------------------------#
:: Elastic IPs

:: Enter Elastic IP id for client 
set ELASTIC_IP_CLIENT=blank

:: Enter Elastic IP for admin
set ELASTIC_IP_SERVER=blank

::-----------------------------------------------------------#
:: Security group, same for both admin and client

set SEC_GROUP=blank

::-----------------------------------------------------------#
:: Subnet, same for both admin and client.

set SUBNET_ID=blank

vagrant %1