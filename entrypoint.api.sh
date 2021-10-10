#!/bin/bash

# service apache2 restart &
chmod 777 -R /var/www/html/storage &
php /var/www/html/artisan config:cache
php /var/www/html/artisan storage:link
if [[ ! -e /var/www/html/storage/oauth-private.key || ! -e /var/www/html/storage/oauth-public.key ]]; then php /var/www/html/artisan passport:install; fi

php /var/www/html/artisan migrate --force

sh /var/www/newrelic-php5-9.18.1.303-linux/newrelic-install install

# service cron start &
redis-server &
# sleep 20
# laravel-echo-server start --force &
# php /var/www/html/artisan queue:work --timeout=0 &
touch /var/www/html/health.ok

apache2ctl -D FOREGROUND

