#!/bin/bash

php /var/www/html/artisan config:cache
php /var/www/html/artisan storage:link
if [[ ! -e /var/www/html/storage/oauth-private.key || ! -e /var/www/html/storage/oauth-public.key ]]; then php /var/www/html/artisan passport:install; fi

php /var/www/html/artisan migrate --force

#New Relilc
nr_enabled=$(printenv ENABLE_NR);
if [ "$nr_enabled" -eq "1" ]; then
  export NR_INSTALL_SILENT=true
  export NR_INSTALL_KEY=$(printenv NR_INSTALL_KEY)
  export nr_name=$(printenv NR_NAME)
  sh /var/www/html/newrelic-php5-10.2.0.314-linux/newrelic-install install
  sed -i -e "s/newrelic.appname =.*/newrelic.appname = \"\Curriki-API $nr_name\"/" /usr/local/etc/php/conf.d/newrelic.ini
fi

h5p_branch=$(printenv H5P_BRANCH);
git clone -b $h5p_branch https://github.com/ActiveLearningStudio/H5P.Distribution.git /tmp/h5p-dist

cd /var/www/html && git log --graph -10 --decorate --pretty > /var/www/html/public/log.txt
cp -rf /tmp/h5p-dist/* /var/www/html/storage/app/public/h5p/
chmod 777 -R /var/www/html/storage &
touch /var/www/html/health.ok

apache2ctl -D FOREGROUND
