# -*- mode: ruby -*-
# vi: set ft=ruby :

$script = <<SCRIPT

echo -e "\n--- Updating ---\n"
sudo apt-get -qq update

echo -e "\n--- Installing Nginx ---\n"
sudo apt-get -y install nginx
sudo cp /vagrant/build/nginx/nginx.conf /etc/nginx/nginx.conf
sudo cp /vagrant/build/nginx/vagrant-app.conf /etc/nginx/conf.d/app.conf
sudo service nginx restart

echo -e "\n--- Setting public directory ---\n"
rm -rf /var/www/html
ln -fs /vagrant/src /var/www/html

echo -e "\n--- Installing PHP 7.2 ---\n"
sudo apt-get -y install python-software-properties
sudo add-apt-repository ppa:ondrej/php
sudo apt-get -qq update
sudo apt-get install -y php7.2 php7.2-fpm php7.2-cli php7.2-common php7.2-mbstring php7.2-gd php7.2-xml php7.2-zip php-xdebug
curl -Ss https://getcomposer.org/installer | php
sudo mv composer.phar /usr/bin/composer

service nginx restart

echo "cd /vagrant/src/" >> /home/vagrant/.bashrc
echo -e "\n--- All set! Rock n' roll! ---\n"
SCRIPT

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial64"
  config.vm.network "forwarded_port", guest: 80, host: 8080 #nginx
  config.vm.provision "shell", inline: $script
end