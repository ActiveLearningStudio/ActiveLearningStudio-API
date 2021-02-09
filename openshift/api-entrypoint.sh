#!/bin/bash

php /var/www/html/artisan config:cache
# php /var/www/html/artisan key:generate
php /var/www/html/artisan passport:install
php /var/www/html/artisan storage:link

apache2ctl -D FOREGROUND