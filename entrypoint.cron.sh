#!/bin/bash

# service apache2 restart &
chmod 777 -R /var/www/html/storage
php /var/www/html/artisan config:cache
if [[ ! -e /var/www/html/storage/oauth-private.key || ! -e /var/www/html/storage/oauth-public.key ]]; then php /var/www/html/artisan passport:install; fi


service cron start &

touch /var/www/html/health.ok
php /var/www/html/artisan queue:work --timeout=0 &


apache2ctl -D FOREGROUND
# while true; do sleep 1000000000000; done
 
