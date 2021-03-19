#!/bin/bash

# service apache2 restart &
service cron start &
redis-server &
laravel-echo-server start --force &
php /var/www/html/artisan queue:work --timeout=0 &

apache2ctl -D FOREGROUND
# while true; do sleep 1000000000000; done
