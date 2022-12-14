#!/bin/bash

php /var/www/html/artisan config:cache
if [[ ! -e /var/www/html/storage/oauth-private.key || ! -e /var/www/html/storage/oauth-public.key ]]; then php /var/www/html/artisan passport:install; fi

#New Relilc
nr_enabled=$(printenv ENABLE_NR);
if [ "$nr_enabled" -eq "1" ]; then
  export NR_INSTALL_SILENT=true
  export NR_INSTALL_KEY=$(printenv NR_INSTALL_KEY)
  export nr_name=$(printenv NR_NAME)
  sh /var/www/html/newrelic-php5-10.3.0.315-linux/newrelic-install install
  sed -i -e "s/newrelic.appname =.*/newrelic.appname = \"\Curriki-API-Cron $nr_name\"/" /usr/local/etc/php/conf.d/newrelic.ini
fi

cp -rf /var/www/html/h5pcode/* /var/www/html/storage/app/public/h5p/
chmod 777 -R /var/www/html/storage &

service cron start &

touch /var/www/html/health.ok
php /var/www/html/artisan queue:work --timeout=0 &


apache2ctl -D FOREGROUND
# while true; do sleep 1000000000000; done
 
