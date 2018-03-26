#!/bin/bash

# Initial server setup
# https://www.digitalocean.com/community/tutorials/initial-server-setup-with-ubuntu-16-04
# Set PasswordAuthentication no
vim /etc/ssh/sshd_config
systemctl reload sshd

# enable firewall
ufw allow 'OpenSSH'
ufw enable

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

# Setup nginx
# https://www.digitalocean.com/community/tutorials/how-to-install-nginx-on-ubuntu-16-04
# https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-in-ubuntu-16-04
ufw allow 'Nginx HTTP'
