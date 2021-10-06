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
    webserver.vm.provision "shell", env: {AWS_ACCESS_KEY_ID:ENV['AWS_ACCESS_KEY_ID'], AWS_SECRET_ACCESS_KEY:ENV['AWS_SECRET_ACCESS_KEY'], AWS_SESSION_TOKEN:ENV['AWS_SESSION_TOKEN']}, inline: <<-SHELL
      apt-get update
      apt-get install -y apache2 php libapache2-mod-php php-mysql php-xml unzip composer

      cp /vagrant/client-website.conf /etc/apache2/sites-available/
      a2ensite client-website
      a2dissite 000-default
      service apache2 reload
      echo $'[default]\n' > credentials
      echo "aws_access_key_id=${AWS_ACCESS_KEY_ID}" >> credentials
      echo $'\n' >> credentials
      echo "aws_secret_access_key=${AWS_SECRET_ACCESS_KEY}" >> credentials
      echo $'\n' >> credentials
      echo "aws_session_token=${AWS_SESSION_TOKEN}" >> credentials
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

    webserverAdmin.vm.provision "shell", inline: <<-SHELL
      apt-get update
      apt-get install -y apache2 php libapache2-mod-php php-mysql mysql-client

      cp /vagrant/admin-website.conf /etc/apache2/sites-available/
      a2ensite admin-website
      a2dissite 000-default
      service apache2 reload

      # Setup database
      # touch ~/.my.cnf
      # mysql -h database-1.crx8snaug9em.us-east-1.rds.amazonaws.com -P 3306 -u database1

    SHELL
    # Print out the Elastic IP Address for easy copy-paste to browser.
    puts "The IP address for Admin Web Server is "  +  ENV["ELASTIC_IP_SERVER"]
  end
end
