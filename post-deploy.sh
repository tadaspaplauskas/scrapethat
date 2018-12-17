#!/bin/bash
composer install
npm install
php artisan migrate
php artisan route:cache
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
