#!/bin/bash

php /var/www/html/artisan config:cache
php /var/www/html/artisan storage:link
if [[ ! -e /var/www/html/storage/oauth-private.key || ! -e /var/www/html/storage/oauth-public.key ]]; then php /var/www/html/artisan passport:install; fi

php /var/www/html/artisan migrate --force

#New Relilc
nr_enabled=$(cat /var/www/html/.env | grep ENABLE_NR);


if [ "$nr_enabled" -eq "1" ]; then
  sh /var/www/cartx-ecomm/newrelic-php5-9.20.0.310-linux/newrelic-install install
  sed -i -e "s/newrelic.appname =.*/newrelic.appname = \"\Laravel API\"/" /usr/local/etc/php/conf.d/newrelic.ini
fi

git clone -b develop https://github.com/ActiveLearningStudio/H5P.Distribution.git /tmp/htp-dist

cp -rf /tmp/htp-dist/* /var/www/html/storage/app/public/h5p/libraries/
chmod 777 -R /var/www/html/storage &
touch /var/www/html/health.ok

apache2ctl -D FOREGROUND

