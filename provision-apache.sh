#!/bin/bash

# BASIC SERVER SETUP
export LANGUAGE=en_US.UTF-8
export LANG=en_US.UTF-8
export LC_ALL=en_US.UTF-8
locale-gen en_US.UTF-8
dpkg-reconfigure locales

sed -i 's/PasswordAuthentication.*/PasswordAuthentication no/' /etc/ssh/sshd_config
systemctl reload sshd

# INSTALL DEPENDENCIES
apt install software-properties-common -y
LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php

apt update
apt upgrade -y

apt install apache2 php7.2 libapache2-mod-php7.2 php-pear php7.2-curl php7.2-gd php7.2-mbstring php7.2-zip php7.2-xml php7.2-mysql mariadb-server mariadb-client

# SETUP FIREWALL
ufw allow 'OpenSSH'
ufw allow 'Apache Full'
ufw enable

# CONFIGURE APACHE
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

echo '<VirtualHost *:80>
    DocumentRoot /var/www/datascraper/current/public
    LogLevel error
    ErrorLog ${APACHE_LOG_DIR}/error.log

    ErrorDocument  404  /
</VirtualHost>
' > /etc/apache2/sites-available/datascraper.conf

a2dissite 000-default
a2ensite datascraper

# CONFIGURE PHP
echo 'date.timezone = UTC' >> /etc/php/7.2/apache2/php.ini
echo 'max_execution_time = 5' >> /etc/php/7.2/apache2/php.ini
echo 'memory_limit = 128M' >> /etc/php/7.2/apache2/php.ini
echo 'expose_php = Off' >> /etc/php/7.2/apache2/php.ini

service apache2 restart

# CONFIGURE MARIADB
mysql_secure_installation
systemctl restart mysql.service
systemctl enable mysql.service

# UTILITIES
echo 'alias ls="ls -halp"' >> ~/.bash_profile


# testing web server
# ab -n 10000 -c 100 -k -l -H "Accept-Encoding: gzip, deflate" http://localhost/login

# finding out how much memory a process is using
#ps aux | grep 'apache2' | awk '{print $6/1024 "mb";}'