#!/bin/bash

php /var/www/html/artisan config:cache
php /var/www/html/artisan storage:link
if [[ ! -e /var/www/html/storage/oauth-private.key || ! -e /var/www/html/storage/oauth-public.key ]]; then php /var/www/html/artisan passport:install; fi

php /var/www/html/artisan migrate --force

sh /var/www/newrelic-php5-9.18.1.303-linux/newrelic-install install
git clone -b develop https://github.com/ActiveLearningStudio/H5P.Distribution.git /tmp/htp-dist

cp -rf /tmp/htp-dist/* /var/www/html/storage/app/public/h5p/libraries/
chmod 777 -R /var/www/html/storage &
service cron start &

php /var/www/html/artisan queue:work --timeout=0 &
touch /var/www/html/health.ok



apache2ctl -D FOREGROUND

