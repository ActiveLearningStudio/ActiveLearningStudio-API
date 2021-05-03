#!/bin/bash


printf yes | php /var/www/html/artisan elastic:migrate:refresh
php /var/www/html/artisan scout:import "App\Models\Activity"
php /var/www/html/artisan scout:import "App\Models\Playlist"
php /var/www/html/artisan scout:import "App\Models\Project"

# while true; do sleep 1000000000000; done
