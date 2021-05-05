#!/bin/bash

# service apache2 restart &
chmod 777 -R /var/www/html/storage
php /var/www/html/artisan config:cache
php /var/www/html/artisan storage:link
if [[ ! -e /var/www/html/oauth-private.key || ! -e /var/www/html/oauth-public.key ]]; then php /var/www/html/artisan passport:install; fi


#temporary for redis
#service cron start &
#redis-server &
#temporary for redis
#sleep 20
#temporary for redis
#laravel-echo-server start --force &
#temporary for redis
#php /var/www/html/artisan queue:work --timeout=0 &
touch /var/www/html/health.ok

apache2ctl -D FOREGROUND
# while true; do sleep 1000000000000; done
 
