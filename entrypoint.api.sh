#!/bin/bash

php /var/www/html/artisan config:cache
php /var/www/html/artisan storage:link
if [[ ! -e /var/www/html/storage/oauth-private.key || ! -e /var/www/html/storage/oauth-public.key ]]; then php /var/www/html/artisan passport:install; fi

php /var/www/html/artisan migrate --force

sh /var/www/newrelic-php5-10.0.0.312-linux/newrelic-install install
git clone -b ${h5pbranch} https://github.com/ActiveLearningStudio/H5P.Distribution.git /tmp/h5p-dist

# cp -rf /tmp/h5p-dist/* /var/www/html/storage/app/public/h5p/
chmod 777 -R /var/www/html/storage &
touch /var/www/html/health.ok

apache2ctl -D FOREGROUND

