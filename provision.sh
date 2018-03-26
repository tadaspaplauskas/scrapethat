#!/bin/bash

# Initial server setup
# https://www.digitalocean.com/community/tutorials/initial-server-setup-with-ubuntu-16-04
# Set PasswordAuthentication no
vim /etc/ssh/sshd_config
systemctl reload sshd

# Dependencies
apt install software-properties-common
add-apt-repository ppa:ondrej/php

apt update
apt upgrade

apt install nginx
apt install php7.2-fpm # must install first to avoid installing apache with php
apt install php7.2-cli
apt install postgres
apt install redis

# Configuration
# ttps://www.digitalocean.com/community/tutorials/how-to-install-nginx-on-ubuntu-16-04
# https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-in-ubuntu-16-04
# https://www.digitalocean.com/community/tutorials/how-to-install-and-use-postgresql-on-ubuntu-16-04

# enable firewall
ufw allow 'Nginx HTTP'
ufw allow 'OpenSSH'
ufw enable
