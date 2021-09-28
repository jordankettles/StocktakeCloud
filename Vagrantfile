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


  # If something is labeled 'Unique', then you should have modified it for your computer.


  config.vm.box = "dummy"

  config.vm.define "webserver" do |webserver|
    webserver.vm.hostname = "webserver"
    webserver.vm.provider :aws do |webserver, override|
      webserver.region = "us-east-1"
      override.nfs.functional = false
      override.vm.allowed_synced_folder_types = :rsync #TODO Learn how to use this properly.
  
      # AWS Configuration.
      
      webserver.keypair_name = "jk-cosc349-lab09" #Unique
      override.ssh.private_key_path = "C:\\Users\\Jordan\\Downloads\\jk-cosc349-lab09.pem" #Unique
  
      # Instance type.
  
      webserver.instance_type = "t2.micro"
  
      # Availability Zone and subnet.
      
      webserver.availability_zone = "us-east-1a"
      webserver.subnet_id = "subnet-ea1336b5"
  
      # Hard disk Image: xenial-64
      webserver.ami = "ami-0133407e358cc1af0"
      
      # Security group.
      webserver.security_groups = "sg-08d8dfe5f8adb992b" #Unique 
      #TODO write up how to manually create this security group.
  
      # Override the ssh username becasue we are using Ubuntu.
      override.ssh.username = "ubuntu"
    end
    webserver.vm.provision "shell", inline: <<-SHELL
      apt-get update
      apt-get install -y apache2 php libapache2-mod-php php-mysql

      # cp /vagrant/client-website.conf /etc/apache2/sites-available/
      # a2ensite client-website
      # a2dissite 000-default
      # service apache2 reload
    SHELL
  end

  config.vm.define "webserverAdmin" do |webserverAdmin|
    webserverAdmin.vm.hostname = "webserverAdmin"
    webserverAdmin.vm.provider :aws do |webserverAdmin, override|
      webserverAdmin.region = "us-east-1"
      override.nfs.functional = false
      override.vm.allowed_synced_folder_types = :rsync
  
      # AWS Configuration.
  
      webserverAdmin.keypair_name = "jk-cosc349-lab09" #Unique
      override.ssh.private_key_path = "C:\\Users\\Jordan\\Downloads\\jk-cosc349-lab09.pem" #Unique
  
      # Instance type.
  
      webserverAdmin.instance_type = "t2.micro"
  
      # Availability Zone and subnet.
      
      webserverAdmin.availability_zone = "us-east-1a"
      webserverAdmin.subnet_id = "subnet-ea1336b5"
  
      # Hard disk Image: xenial-64
      webserverAdmin.ami = "ami-0133407e358cc1af0"
      
      # Security group.
      webserverAdmin.security_groups = "sg-08d8dfe5f8adb992b" #Unique
  
      # Override the ssh username becasue we are using Ubuntu.
      override.ssh.username = "ubuntu"
    end

    webserverAdmin.vm.provision "shell", inline: <<-SHELL
      apt-get update
      apt-get install -y apache2 php libapache2-mod-php php-mysql

      # cp /vagrant/admin-website.conf /etc/apache2/sites-available/
      # a2ensite admin-website
      # a2dissite 000-default
      # service apache2 reload
    SHELL
  end
end
