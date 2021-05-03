#!/bin/bash


php /var/www/html/artisan config:cache


touch /var/www/html/health.ok

apache2ctl -D FOREGROUND
# while true; do sleep 1000000000000; done
