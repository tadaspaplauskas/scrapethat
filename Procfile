release: php artisan migrate --force
web: $(composer config bin-dir)/heroku-php-apache2 public/
worker: php artisan queue:work --sleep=3 --tries=3
