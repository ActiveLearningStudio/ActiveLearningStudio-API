#!/bin/bash

migratedone=`cat migrate.done`
status="Done"
if [ "$migratedone" = "$status" ]; then
    echo "Elastic search has already run"
else
    printf yes | php /var/www/html/artisan elastic:migrate:refresh
    php /var/www/html/artisan scout:import "App\Models\Activity"
    php /var/www/html/artisan scout:import "App\Models\Playlist"
    php /var/www/html/artisan scout:import "App\Models\Project"
    echo "Done" > migrate.done
fi
