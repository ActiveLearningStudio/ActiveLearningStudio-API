#!/bin/bash

# list all folders
# folders=$(ls -l /var/www/html/storage) # Find original_size / names
base_path=/var/www/html/storage/
for i in $(ls ${base_path}imported-projects); do
  for j in $(ls ${base_path}imported-projects/$i); do
    # json_file = $(find ${base_path}imported-projects/$i/$j -iname *.json)
    echo $(jq  '.size' ${base_path}imported-projects/$i/$j)
  done
  
done

# list all files inside the folders

# for (files in folders)
# {
#     json_file = file.json
#     required_size = jq  '.size' json_file
#     if(required_size == original_size){
#         response = php artisan import:project /var/www/html/storage/.file_name
#         if (response.success){
#             mv file_name processed
#         } else {
#             mv file_name errors
#         }
        
#     }
# }

