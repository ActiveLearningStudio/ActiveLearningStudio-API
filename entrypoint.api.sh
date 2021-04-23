#!/bin/bash

# service apache2 restart &
php /var/www/html/artisan config:cache
php /var/www/html/artisan storage:link
php /var/www/html/artisan passport:install
service cron start &
redis-server &
laravel-echo-server start --force &
php /var/www/html/artisan queue:work --timeout=0 &

apache2ctl -D FOREGROUND
# while true; do sleep 1000000000000; done
