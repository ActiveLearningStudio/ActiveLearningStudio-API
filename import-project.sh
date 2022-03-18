#!/bin/bash

# list all folders
# folders=$(ls -l /var/www/html/storage) # Find original_size / names
base_path=/
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
        success=$(echo "$response" | jq '.success')
        # echo $response
         
        if [[ $success == true ]]
        
        then
            rm -rf ${base_path}imported-projects/$i
            echo 'response true'
            # mv ${base_path}imported-projects/$i ${base_path}success-imports
        else
            echo 'response false'
            # mv ${base_path}imported-projects/$i ${base_path}error-imports
        fi
    else
        echo "Size are not equal.. Continue..."
    fi
  
done