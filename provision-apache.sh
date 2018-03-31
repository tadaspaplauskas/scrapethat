#!/bin/bash

# Initial server setup
# https://www.digitalocean.com/community/tutorials/initial-server-setup-with-ubuntu-16-04
# Set PasswordAuthentication no
vim /etc/ssh/sshd_config
systemctl reload sshd

apt update && apt upgrade -y

# basic dependencies
apt install software-properties-common -y
LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php

apt update

apt install apache2 php7.2 libapache2-mod-php7.2 php-pear php7.2-curl php7.2-gd php7.2-mbstring php7.2-zip php7.2-xml mariadb-server mariadb-client

# enable firewall
ufw allow 'OpenSSH'
ufw allow 'Apache Full'
ufw enable

# configure server
# adjust number of workers based on available ram
sed -i 's/MaxSpareServers.*/MaxSpareServers 20/' /etc/apache2/mods-enabled/mpm_prefork.conf
sed -i 's/MaxRequestWorkers.*/MaxRequestWorkers 40/' /etc/apache2/mods-enabled/mpm_prefork.conf
# 0 would be fine; consider setting to 10000 to avoid possible memory leaks
sed -i 's/MaxConnectionsPerChild.*/MaxConnectionsPerChild 10000/' /etc/apache2/mods-enabled/mpm_prefork.conf
# The Timeout setting is the number of seconds before data "sends" or "receives" (to or from the client) time out. Having this set to a high number forces site visitors to "wait in line" which adds extra load to the server. 
sed -i 's/Timeout.*/Timeout 30/' /etc/apache2/apache2.conf
sed -i 's/KeepAlive.*/KeepAlive Off/' /etc/apache2/apache2.conf
echo 'ServerName 195.201.102.167' >> /etc/apache2/apache2.conf
echo 'ServerSignature Off' >> /etc/apache2/apache2.conf
echo 'ServerTokens Prod' >> /etc/apache2/apache2.conf

# setup vhost
echo '<VirtualHost *:80>
    DocumentRoot /var/www/datascraper
    LogLevel error
    ErrorLog ${APACHE_LOG_DIR}/error.log
</VirtualHost>
' > /etc/apache2/sites-available/datascraper.conf

a2enmod rewrite
a2dissite 000-default
a2ensite datascraper

# configure apache's php
echo 'date.timezone = UTC' >> /etc/php/7.2/apache2/php.ini
# aggresive timeout to avoid hanging processe
echo 'max_execution_time = 5' >> /etc/php/7.2/apache2/php.ini
echo 'memory_limit = 128M' >> /etc/php/7.2/apache2/php.ini
echo 'expose_php = Off' >> /etc/php/7.2/apache2/php.ini

service apache2 reload

# configure database
mysql_secure_installation
systemctl restart mysql.service
systemctl enable mysql.service

# misc goodies
echo 'alias ls="ls -halp"' >> ~/.bash


# testing web server
# ab -n 10000 -c 100 -k -H -l http://localhost/info.php

# finding out how much memory a process is using
#ps aux | grep 'apache2' | awk '{print $6/1024 "mb";}'