#! /bin/sh
# Run this script before vagrant up
# Need to fill out the export fields with
# your personal values

#-----------------------------------------------------------#
## AWS credentials
export AWS_ACCESS_KEY_ID=
export AWS_SECRET_ACCESS_KEY=
export AWS_SESSION_TOKEN=

export SNS_KEY=
export SNS_SECRET=

## Keypair 

# Enter the keypair name associated with your aws account
export KEYPAIR_NAME=

# Enter keypair path on your machine 
export PRIVATE_KEY_PATH=

#-----------------------------------------------------------#
## Elastic ips

# Enter elastic ip id for client 
export ELASTIC_IP_CLIENT=

# Enter elastic ip for admin
export ELASTIC_IP_SERVER=

#-----------------------------------------------------------#
## Security group, same for both admin and client
export SEC_GROUP=

#-----------------------------------------------------------#
## Subnets

# Subnet id for client 
export SUBNET_ID_CLIENT=

# Subnet id for admin
export SUBNET_ID_ADMIN=
#-----------------------------------------------------------#

