#!/bin/bash

# service apache2 restart &
chmod 777 -R /var/www/html/storage
php /var/www/html/artisan config:cache
if [[ ! -e /var/www/html/storage/oauth-private.key || ! -e /var/www/html/storage/oauth-public.key ]]; then php /var/www/html/artisan passport:install; fi


service cron start &
redis-server &
laravel-echo-server start --force &
php /var/www/html/artisan queue:work --timeout=0 &
touch /var/www/html/health.ok

apache2ctl -D FOREGROUND
# while true; do sleep 1000000000000; done
 
