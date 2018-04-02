#!/bin/bash

# BASIC SERVER SETUP
export LANGUAGE=en_US.UTF-8
export LANG=en_US.UTF-8
export LC_ALL=en_US.UTF-8
locale-gen en_US.UTF-8
dpkg-reconfigure locales

sed -i 's/PasswordAuthentication.*/PasswordAuthentication no/' /etc/ssh/sshd_config
systemctl reload sshd

sysctl -w vm.swappiness=0

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

a2enmod expires
# A604800 => cache for 7 days after client's access time
echo '<VirtualHost *:80>
    DocumentRoot /var/www/datascraper/current/public
    DirectoryIndex /index.php 
    FallbackResource /index.php

    ExpiresActive On
    ExpiresByType image/jpg A604800
    ExpiresByType image/png A604800
    ExpiresByType image/gif A604800
    ExpiresByType image/jpeg A604800
    ExpiresByType text/css A604800
    ExpiresByType text/javascript A604800
    ExpiresByType application/x-javascript A604800
    ExpiresByType application/javascript A604800

    LogLevel error
    ErrorLog ${APACHE_LOG_DIR}/error.log
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
echo '[mysqld]' >> /etc/mysql/mariadb.cnf
echo 'innodb_file_per_table=1' >> /etc/mysql/mariadb.cnf
echo 'innodb_buffer_pool_size=256M' >> /etc/mysql/mariadb.cnf
echo 'host_cache_size=16' >> /etc/mysql/mariadb.cnf
echo 'key_buffer_size=64K' >> /etc/mysql/mariadb.cnf
echo 'max_connections=200' >> /etc/mysql/mariadb.cnf
echo 'thread_cache_size=200' >> /etc/mysql/mariadb.cnf
echo 'slow_query_log=1' >> /etc/mysql/mariadb.cnf
echo 'slow_query_log_file=slow_queries.log' >> /etc/mysql/mariadb.cnf
echo 'long_query_time=3' >> /etc/mysql/mariadb.cnf
echo 'skip-name-resolve' >> /etc/mysql/mariadb.cnf
echo 'query_cache_type=1' >> /etc/mysql/mariadb.cnf
echo 'query_cache_size=128M' >> /etc/mysql/mariadb.cnf
echo 'query_cache_limit=256K' >> /etc/mysql/mariadb.cnf
echo 'query_cache_min_res_unit=2K' >> /etc/mysql/mariadb.cnf

# CREATE DATABASE AND USER
echo 'Enter database password'
read DATABASE

DB_PASSWORD='$(openssl rand -base64 20)'
echo 'Generated password: '
echo DB_PASSWORD

mysql -e 'CREATE DATABASE ${DATABASE};'
mysql -e 'CREATE USER ${DATABASE}@"127.0.0.1" IDENTIFIED BY "${DB_PASSWORD}";'
mysql -e 'GRANT ALL PRIVILEGES ON ${DATABASE}.* TO "${DATABASE}"@"127.0.0.1";'

systemctl restart mysql.service
systemctl enable mysql.service

# UTILITIES
echo 'alias ls="ls -halp"' >> ~/.bash_profile

git clone git@github.com:tadaspaplauskas/vimrc.git


# testing web server configuration
# ab -n 10000 -c 100 -k -l -H "Accept-Encoding: gzip, deflate" http://localhost/login

# finding out how much memory a process is using
#ps aux | grep 'apache2' | awk '{print $6/1024 "mb";}'


#################################################
# this is unfinished nginx+fpm based provisioning script
# #!/bin/bash

# # Initial server setup
# # https://www.digitalocean.com/community/tutorials/initial-server-setup-with-ubuntu-16-04
# # Set PasswordAuthentication no
# vim /etc/ssh/sshd_config
# systemctl reload sshd

# # Dependencies
# apt install software-properties-common
# add-apt-repository ppa:ondrej/php
# LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/nginx

# apt update
# apt upgrade

# apt install nginx
# apt install php7.2-fpm # must install first to avoid installing apache with php
# apt install php7.2-cli
# apt install postgres
# apt install redis

# # Configuration
# # ttps://www.digitalocean.com/community/tutorials/how-to-install-nginx-on-ubuntu-16-04
# # https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-in-ubuntu-16-04
# # https://www.digitalocean.com/community/tutorials/how-to-install-and-use-postgresql-on-ubuntu-16-04

# # enable firewall
# ufw allow 'Nginx HTTP'
# ufw allow 'OpenSSH'
# ufw enable
