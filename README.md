
CurrikiGo 
==========

This tool (a tsugi module) serve CurrikiStudio Programs / Playlists / Activites.

Installation
-------------------

1 - Move to tsugi's mod directory:

    cd tsugi-directory/mod/

2 - Clone this repository as "curriki":

    git clone https://github.com/ActiveLearningStudio/curriki-tsugi-module.git curriki

3 - Copy config-sample.php and rename as config.php

4 - Make Composer install the dependencies

    composer install
    composer dump-autoload

5 - Optional - For hosted CurrikiStudio (or local development) set CURRIKI_STUDIO_HOST in config.php

    define('CURRIKI_STUDIO_HOST', 'https://your-currikistudio-host.com');
