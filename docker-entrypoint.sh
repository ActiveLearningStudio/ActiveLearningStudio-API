#!/bin/bash

touch .env
echo "APP_NAME=Curriki" >> .env
echo "APP_ENV=local" >> .env
echo "APP_KEY=" >> .env
echo "APP_DEBUG=true" >> .env
echo "APP_URL=${APP_URL}" >> .env
echo "FRONT_END_URL=${FRONT_END_URL}" >> .env
echo "LOG_CHANNEL=stack" >> .env
echo "DB_CONNECTION=${DB_CONNECTION}" >> .env
echo "DB_HOST=${DB_HOST}" >> .env
echo "DB_PORT=${DB_PORT}" >> .env
echo "DB_DATABASE=${DB_DATABASE}" >> .env
echo "DB_USERNAME=${DB_USERNAME}" >> .env
echo "DB_PASSWORD=${DB_PASSWORD}" >> .env
echo "BROADCAST_DRIVER=log" >> .env
echo "CACHE_DRIVER=file" >> .env
echo "QUEUE_CONNECTION=sync" >> .env
echo "SESSION_DRIVER=file" >> .env
echo "SESSION_LIFETIME=120" >> .env
echo "REDIS_HOST=127.0.0.1" >> .env
echo "REDIS_PASSWORD=null" >> .env
echo "REDIS_PORT=6379" >> .env
echo "MAIL_MAILER=smtp" >> .env
echo "MAIL_HOST=smtp.mailtrap.io" >> .env
echo "MAIL_PORT=2525" >> .env
echo "MAIL_USERNAME=null" >> .env
echo "MAIL_PASSWORD=null" >> .env
echo "MAIL_ENCRYPTION=null" >> .env
echo "MAIL_FROM_ADDRESS=null" >> .env
echo "MAIL_FROM_NAME="${APP_NAME}"" >> .env
echo "AWS_ACCESS_KEY_ID=" >> .env
echo "AWS_SECRET_ACCESS_KEY=" >> .env
echo "AWS_DEFAULT_REGION=us-east-1" >> .env
echo "AWS_BUCKET=" >> .env
echo "PUSHER_APP_ID=" >> .env
echo "PUSHER_APP_KEY=" >> .env
echo "PUSHER_APP_SECRET=" >> .env
echo "PUSHER_APP_CLUSTER=mt1" >> .env
echo "MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"" >> .env
echo "MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"" >> .env
echo "MATTERMOST_SERVER=default" >> .env
echo "MATTERMOST_HOST=" >> .env
echo "MATTERMOST_LOGIN=" >> .env
echo "MATTERMOST_LOGIN=" >> .env
echo "MATTERMOST_PASSWORD=" >> .env
echo "MATTERMOST_DEFAULT_TEAM_ID=" >> .env
echo "GAPI_CLASSROOM_CREDENTIALS=" >> .env
echo "GAPI_CLIENT_ID=" >> .env

php artisan key:generate
php artisan cache:clear
php artisan config:cache


exec "$@"