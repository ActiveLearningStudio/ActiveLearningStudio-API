#!/bin/bash

php /var/www/html/artisan config:cache
php /var/www/html/artisan storage:link
if [[ ! -e /var/www/html/storage/oauth-private.key || ! -e /var/www/html/storage/oauth-public.key ]]; then php /var/www/html/artisan passport:install; fi

php /var/www/html/artisan migrate --force

git clone -b develop https://github.com/ActiveLearningStudio/H5P.Distribution.git /tmp/h5p-dist

cp -rf /tmp/h5p-dist/* /var/www/html/storage/app/public/h5p/
chmod 777 -R /var/www/html/storage &
service cron start &

php /var/www/html/artisan queue:work --timeout=0 &

echo "192.168.100.1 learn.noovo.co" >> /etc/hosts
echo "192.168.100.1 lms.noovo.co" >> /etc/hosts
echo "192.168.100.1 tsugi.noovo.co" >> /etc/hosts
echo "192.168.100.1 trax.noovo.co" >> /etc/hosts

touch /var/www/html/health.ok



apache2ctl -D FOREGROUND

