#!/bin/bash

STORAGE_ZIP=${STORAGE_ZIP:-https://raw.githubusercontent.com/ActiveLearningStudio/curriki-eks/develop/storage/storage.zip}

if test ! -f '/var/www/html/storage/storage.zip'; then
    curl -L --compressed -o '/var/www/html/storage/storage.zip' "${STORAGE_ZIP}"
    cd /var/www/html/storage && unzip -q storage.zip
fi
