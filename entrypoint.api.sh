#!/bin/bash

chmod 777 -R /var/www/html/storage &
php /var/www/html/artisan config:cache
php /var/www/html/artisan storage:link
if [[ ! -e /var/www/html/storage/oauth-private.key || ! -e /var/www/html/storage/oauth-public.key ]]; then php /var/www/html/artisan passport:install; fi

php /var/www/html/artisan migrate --force

sh /var/www/newrelic-php5-9.18.1.303-linux/newrelic-install install
git clone https://github.com/ActiveLearningStudio/h5p-course-presentation.git /var/www/html/storage/app/public/h5p-course-presentation-ali

touch /var/www/html/health.ok

apache2ctl -D FOREGROUND

