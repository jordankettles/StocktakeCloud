# -*- mode: ruby -*-
# vi: set ft=ruby :

class Hash
  def slice(*keep_keys)
    h = {}
    keep_keys.each { |key| h[key] = fetch(key) if has_key?(key) }
    h
  end unless Hash.method_defined?(:slice)
  def except(*less_keys)
    slice(*keys - less_keys)
  end unless Hash.method_defined?(:except)
end

Vagrant.configure("2") do |config|


  # If something is labeled 'Unique', then you should have it set as a environment variable.

  config.vm.box = "dummy"

  config.vm.define "webserver" do |webserver|
    webserver.vm.hostname = "webserver"
    webserver.vm.provider :aws do |webserver, override|
      webserver.region = "us-east-1"
      override.nfs.functional = false
      override.vm.allowed_synced_folder_types = :rsync
      override.vm.synced_folder "client", "/vagrant"
  
      # AWS Configuration.
      
      webserver.keypair_name = ENV["KEYPAIR_NAME"] #Unique
      override.ssh.private_key_path = ENV["PRIVATE_KEY_PATH"] #Unique
  
      # Instance type.
  
      webserver.instance_type = "t2.micro"
  
      # Availability Zone and subnet.
      
      webserver.availability_zone = "us-east-1a"
      webserver.subnet_id = ENV["SUBNET_ID_CLIENT"] #Unique 
  
      # Hard disk Image: xenial-64
      webserver.ami = "ami-0133407e358cc1af0"
      
      # Security group.
      webserver.security_groups = ENV["SEC_GROUP"] #Unique #TODO write up how to manually create this security group.

      # Elastic IP Address
      # This is optional, it can be commented out if you don't want to use Elastic IP Addresses.
      webserver.elastic_ip = ENV["ELASTIC_IP_CLIENT"] #Unique 
  
      # Override the ssh username becasue we are using Ubuntu.
      override.ssh.username = "ubuntu"
    end
    webserver.vm.provision "shell", env: {SNS_KEY:ENV['SNS_KEY'], SNS_SECRET:ENV['SNS_SECRET']}, inline: <<-SHELL
      apt-get update
      apt-get install -y apache2 php libapache2-mod-php php-mysql php-xml unzip composer

      cp /vagrant/client-website.conf /etc/apache2/sites-available/
      a2ensite client-website
      a2dissite 000-default
      service apache2 reload

      # setup AWS.
      echo $'[default]\n' > credentials
      echo "aws_access_key_id=${SNS_KEY}" >> credentials
      echo $'\n' >> credentials
      echo "aws_secret_access_key=${SNS_SECRET}" >> credentials
      mkdir /.aws
      mv credentials /.aws/.
      chmod 777 /.aws/credentials
      cd /vagrant/
      composer require aws/aws-sdk-php
    SHELL
    
    # Print out the Elastic IP Address for easy copy-paste to browser.
    puts "The IP address for the Client Web Server is " + ENV["ELASTIC_IP_CLIENT"]
  end

  config.vm.define "webserverAdmin" do |webserverAdmin|
    webserverAdmin.vm.hostname = "webserverAdmin"
    webserverAdmin.trigger.before :destroy do |trigger|
      trigger.run_remote = {inline: "bash -c 'export MYSQL_PWD=\'database2\'; echo \"DROP DATABASE stocktake;\" | mysql -h database-2.crx8snaug9em.us-east-1.rds.amazonaws.com -P 3306 -u database2'"}
    end

    webserverAdmin.vm.provider :aws do |webserverAdmin, override|
      webserverAdmin.region = "us-east-1"
      override.nfs.functional = false
      override.vm.allowed_synced_folder_types = :rsync
      override.vm.synced_folder "admin", "/vagrant"
  
      # AWS Configuration.
  
      webserverAdmin.keypair_name = ENV["KEYPAIR_NAME"] #Unique
      override.ssh.private_key_path = ENV["PRIVATE_KEY_PATH"] #Unique
  
      # Instance type.
  
      webserverAdmin.instance_type = "t2.micro"
  
      # Availability Zone and subnet.
      
      webserverAdmin.availability_zone = "us-east-1a"
      webserverAdmin.subnet_id = ENV["SUBNET_ID_ADMIN"] # Unique
  
      # Hard disk Image: xenial-64
      webserverAdmin.ami = "ami-0133407e358cc1af0"
      
      # Security group.
      webserverAdmin.security_groups = ENV["SEC_GROUP"] #Unique 

      # Elastic IP Address
      # This is optional, it can be commented out if you don't want to use Elastic IP Addresses.
      webserverAdmin.elastic_ip = ENV["ELASTIC_IP_SERVER"] #Unique 
  
      # Override the ssh username becasue we are using Ubuntu.
      override.ssh.username = "ubuntu"

      
    end

    webserverAdmin.vm.provision "shell", env: {SNS_KEY:ENV['SNS_KEY'], SNS_SECRET:ENV['SNS_SECRET']}, inline: <<-SHELL
      apt-get update
      apt-get install -y apache2 php libapache2-mod-php php-mysql mysql-client php-xml unzip composer

      cp /vagrant/admin-website.conf /etc/apache2/sites-available/
      a2ensite admin-website
      a2dissite 000-default
      service apache2 reload

      # setup AWS
      echo $'[default]\n' > credentials
      echo "aws_access_key_id=${SNS_KEY}" >> credentials
      echo $'\n' >> credentials
      echo "aws_secret_access_key=${SNS_SECRET}" >> credentials
      mkdir /.aws
      mv credentials /.aws/.
      chmod 777 /.aws/credentials
      cd /vagrant/
      composer require aws/aws-sdk-php

      # Setup database
      # touch ~/.my.cnf
      export MYSQL_PWD='database2'
      echo "CREATE DATABASE stocktake;" | mysql -h database-2.crx8snaug9em.us-east-1.rds.amazonaws.com -P 3306 -u database2
      cat /vagrant/setup-database.sql | mysql -h database-2.crx8snaug9em.us-east-1.rds.amazonaws.com -P 3306 -u database2 stocktake
      cat /vagrant/demo-values.sql | mysql -h database-2.crx8snaug9em.us-east-1.rds.amazonaws.com -P 3306 -u database2 stocktake

    SHELL
    # Print out the Elastic IP Address for easy copy-paste to browser.
    puts "The IP address for Admin Web Server is "  +  ENV["ELASTIC_IP_SERVER"]
  end
end
