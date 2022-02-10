#!/bin/bash

# list all folders
# folders=$(ls -l /var/www/html/storage) # Find original_size / names
base_path=/imported-projects/
for i in $(ls ${base_path}imported-projects); do
    json_file=$(ls ${base_path}imported-projects/$i | grep json)
    file_size=$(jq '.size' ${base_path}imported-projects/$i/$json_file | tr -d '"')
    

    zip_file=$(ls ${base_path}imported-projects/$i | grep -v json)
    original_size=$(stat --printf="%s" ${base_path}imported-projects/$i/$zip_file)
    echo  $file_size
    echo $original_size
    if [ $file_size == $original_size ]
    then
        echo "Size are equal"
        response=$(/usr/local/bin/php /var/www/html/artisan import:project ${base_path}imported-projects/$i/$zip_file)
        # response="{\"success\":false}"
        success=$(jq '.success'  <<< $response)
        # echo $response
        # echo $success
        if [ $success == 'true' ]
        
        then
            echo 'trueeing'
            # mv ${base_path}imported-projects/$i ${base_path}success-imports
        else
            echo 'falseeing'
            # mv ${base_path}imported-projects/$i ${base_path}error-imports
        fi
    else
        echo "Size are not equal.. Continue..."
    fi
  
done
