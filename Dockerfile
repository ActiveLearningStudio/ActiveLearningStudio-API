FROM php:7.3-apache

# Add apache and php config for Laravel
COPY ./site.conf /etc/apache2/sites-available/site.conf
RUN ln -s /etc/apache2/sites-available/site.conf /etc/apache2/sites-enabled/
#RUN sed -i 's/Listen 80/Listen 8000/g' /etc/apache2/ports.conf
RUN a2dissite 000-default.conf && a2ensite site.conf && a2enmod rewrite && a2enmod headers

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer.lock and composer.json
#COPY composer.lock composer.json /var/www/
# Set working directory
WORKDIR /var/www
# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    jq \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    cron \
    wget \
    && docker-php-ext-install opcache \
    && docker-php-ext-configure pgsql \
    && docker-php-ext-install pdo pdo_pgsql \
    && pecl install apcu && docker-php-ext-enable apcu opcache

RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

RUN apt-get install -y locales locales-all
ENV LC_ALL en_US.UTF-8
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US.UTF-8

# Change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

WORKDIR /var/www/html

#New Relic
RUN wget https://download.newrelic.com/php_agent/release/newrelic-php5-10.3.0.315-linux.tar.gz && \
	tar -xzf newrelic-php5-10.3.0.315-linux.tar.gz

# Copy the PHP configuration file
COPY ./php.ini /usr/local/etc/php/
#COPY . /var/www/html/




# Cron Jobs

# Copy hello-cron file to the cron.d directory
# COPY ./scheduler.cron /etc/cron.d/root

# Give execution rights on the cron job
# RUN chmod 0644 /etc/cron.d/root

# Apply cron job
# RUN crontab /etc/cron.d/root

# RUN touch /var/log/cron.log


#RUN service apache2 restart

#RUN chown -R 1000 /var/www/html


COPY . .
RUN apt-get install gnupg -y
ARG dopplersecret
RUN chmod 777 -R /var/www/html/storage
RUN (curl -Ls https://cli.doppler.com/install.sh || wget -qO- https://cli.doppler.com/install.sh) | sh
RUN DOPPLER_TOKEN=$dopplersecret doppler secrets download --no-file --format env > .env
RUN composer install
RUN php /var/www/html/artisan optimize
RUN php /var/www/html/artisan config:cache
RUN cat /var/www/html/.env
RUN ls /var/www/html
RUN ls /var/www/html/storage
RUN ls /var/www/html/vendor
RUN php /var/www/html/artisan test

RUN composer install --no-dev --prefer-dist --optimize-autoloader && \
    composer clear-cache


# USER root
# COPY ./entrypoint.api.sh /var/www/html/
# RUN chmod 777 /var/www/html/entrypoint.api.sh
# RUN chmod +x /var/www/html/entrypoint.api.sh

COPY ./entrypoint.api.sh ./
RUN chmod +x /var/www/html/entrypoint.api.sh

ENTRYPOINT ["sh", "/var/www/html/entrypoint.api.sh"]
