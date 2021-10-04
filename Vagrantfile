# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  
  config.vm.box = "ubuntu/xenial64"

  # Disable the default vagrant synced folder.
  config.vm.synced_folder ".", "/vagrant", disabled: true

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine and only allow access
  # via 127.0.0.1 to disable public access

  # PRE SPLIT 
  # config.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
  config.vm.define "webserver" do |webserver|
    webserver.vm.hostname = "webserver"
    # webserver.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
    webserver.vm.network "private_network", ip: "192.168.2.13"

    # Create a synced folder between webserver and the client folder of the repo.
    webserver.vm.synced_folder "client", "/vagrant"

    webserver.vm.provision "shell", inline: <<-SHELL
      apt-get update
      apt-get install -y apache2 php libapache2-mod-php php-mysql

      cp /vagrant/client-website.conf /etc/apache2/sites-available/
      a2ensite client-website
      a2dissite 000-default
      service apache2 reload
    SHELL
  end

  config.vm.define "webserverAdmin" do |webserverAdmin|
    webserverAdmin.vm.hostname = "webserverAdmin"
    # webserverAdmin.vm.network "forwarded_port", guest: 81, host: 8081, host_ip: "127.0.0.1"
    webserverAdmin.vm.network "private_network", ip: "192.168.2.11"

    # Create a synced folder between admin server and the admin folder.
    webserverAdmin.vm.synced_folder "admin", "/vagrant"

    webserverAdmin.vm.provision "shell", inline: <<-SHELL
      apt-get update
      apt-get install -y apache2 php libapache2-mod-php php-mysql

      cp /vagrant/admin-website.conf /etc/apache2/sites-available/
      a2ensite admin-website
      a2dissite 000-default
      service apache2 reload
    SHELL
  end

  config.vm.define "dbserver" do |dbserver|
    dbserver.vm.hostname = "dbserver"
    dbserver.vm.network "private_network", ip: "192.168.2.12"

    # Create a synced folder between the database and the db folder.
    dbserver.vm.synced_folder "db", "/vagrant"

    dbserver.vm.provision "shell", inline: <<-SHELL
      apt-get update
      
      apt-get -y install mysql-client
     
      # service mysql restart
    SHELL
  end
end

